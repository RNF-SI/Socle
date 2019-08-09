function NodeCheckBox(props) {
    return <input type="checkbox" id={props.id} checked={props.checked} onChange={props.onChange} disabled={props.active === false} />
}

function NodeNull(props) {
    return (
        <li>
            <NodeCheckBox checked={props.checked} onChange={props.onChange} />
            Non concerné
        </li>
    );
}

// Composant permettant d'ajouter un item de QCM
class NewNode extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            open: false,
            label: ""
        }
    }

    onClickSave = (e) => {
        e.preventDefault();

        const url = site_url("api/create_qcm_item/" + this.props.parent_id);
        $.post(url, {label: this.state.label}, (resp) => {
            if (resp.success) {
                this.setState({label: "", open:false});
                this.props.reloadItems();
            }
        });
    }

    onClickOpen = (e) => {
        e.preventDefault();
        this.setState({open: !this.state.open});
    }

    handleChange = (e) => {
        this.setState({label: e.target.value});
    }

    render() {
        let inputWidget = <div><input type="text" placeholder="Intitulé de l'item" value={this.state.label} onChange={this.handleChange} />
            &nbsp;<a href="#" onClick={this.onClickSave}><span className="fas fa-save"></span></a></div>;

        return (<li className="tree-add-item" onClick={(e) => {e.stopPropagation()}}>
            <a href="#" onClick={this.onClickOpen}>
                <span className={this.state.open ? "fas fa-minus-circle" : "fas fa-plus-circle"}> </span>
            </a>&nbsp;
            {this.state.open ? inputWidget : "Ajouter un item..."}
            </li>);
    }
}


class TreeNode extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            subnodes: [],
            loadingSubnodes: false,
            expanded: false,
            terminal: false,
            childrenNulled: false
        }
    }

    getData = (prop) => this.props.getNodeData(this.props.node_id, prop)


    fetchSubNodes = () => {
        this.setState({loadingSubnodes: true});
        const url = site_url('api/get_child_nodes/' + this.props.node_id);
        $.get(url, (data) => {
            this.props.addCallback(this.props.node_id, data);
            this.setState({
                subnodes: data,
                loadingSubnodes: false,
                terminal: data.length == 0
            });
            this.setExpanded(true);
        })
    }

    onClickExpand = (e) => {
        if (e) e.stopPropagation();
        if (this.isExpanded()) {
            this.setExpanded(false);
        } else {
            if (this.state.subnodes.length > 0 || this.state.terminal || (this.isActive() === false)) {
                this.setExpanded(true);
                return
            }
            this.fetchSubNodes();
        }
    }

    setExpanded = (exp) => { this.props.changeCallback(this.props.node_id, {expanded: exp}) }

    onCheckboxChecked = (e) => {
        e.stopPropagation();
        if (! this.isActive()) return;
        const checked = !this.isChecked();
        let changes = {checked: checked};
        this.onClickExpand();
        this.props.changeCallback(this.props.node_id, changes);
    }

    onRemarquableClick = (e) => {
        e.preventDefault();
        let changes = {checked: true, remarquable: !this.isRemarquable()};

        this.props.changeCallback(this.props.node_id, changes);
    }

    isExpanded = () => this.props.data[this.props.node_id].expanded

    isChecked = () => this.getData('checked')

    isActive = () => {
        let active = this.props.data[this.props.node_id].active;
        if (active === undefined)
            return true;
        return active;
    }

    isRemarquable = () => {
        return this.isChecked() && this.getData('remarquable');
    }

    render() {
        let checkbox, description, definition, newItemNode, remarquable;

        if (this.props.checkable) {
            checkbox = <NodeCheckBox id={"chkbx-" + this.props.node_id}
                checked={this.isChecked()}
                onChange={this.onCheckboxChecked} active={this.isActive()} />;
        }

        if (this.isChecked() && !this.props.nullying) {
            let buttonEditRem;
            if (this.isRemarquable()) buttonEditRem = <a className="remarquable-edit"
                onClick={this.props.openModalCallback} href="#" title="critères de remarquabilité">
                    <span className="fas fa-edit" >&nbsp;</span>
                </a>;
            remarquable = <span className={"remarquable-control " + (this.isRemarquable() ? "remarquable" : "")}>
                <a className="coche-remarquable" href="#" title="Signaler cet élément comme remarquable"
                onClick={this.onRemarquableClick}>★&nbsp;</a>{buttonEditRem}
                </span>;
        }

        if (this.props.description) {
            description = <div className="rubrique-description" dangerouslySetInnerHTML={{__html: this.props.description}}></div>
        }

        if (this.props.definition) {
            definition = <span className="description-tooltip" data-toggle="popover" data-content={this.props.definition}>?</span>
        }

        if (this.state.subnodes.length > 0 && this.props.checkable) {
            newItemNode = <NewNode parent_id={this.props.node_id} reloadItems={this.fetchSubNodes} />
        }

        activate_popover("body");

        return (
            <li key={this.props.node_id}  className={this.props._class}>
                {checkbox}{checkbox ? " " : ""}
                <span className="tree-item-label" onClick={this.onClickExpand}>{this.props.label}{definition}&nbsp;{remarquable}&nbsp;
                {this.state.terminal ? "" : <span className={this.isExpanded()  ? "fas fa-chevron-down" : "fas fa-chevron-right"}></span>}
                </span>
                {description}
                <ul key={'cont-' + this.props.node_id.toString()} className={this.isExpanded() ? "node-visible" : "node-hidden"}>
                    {this.state.subnodes.map(node => (
                        <TreeNode label={node.label} node_id={node.id} key={'node-' + node.id}
                            className={node.class}
                            description={node.description}
                            definition={node.definition}
                            checkable={node.checkable}
                            nullying={node.nullying}
                            data={this.props.data}
                            getNodeData={this.props.getNodeData}
                            changeCallback={this.props.changeCallback}
                            addCallback={this.props.addCallback}
                            openModalCallback={this.props.openModalCallback} />
                    ))}
                    {newItemNode}
                </ul>
            </li>
        );
    }
}