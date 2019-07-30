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


class TreeNode extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            subnodes: [],
            loadingSubnodes: false,
            expanded: false,
            terminal: false,
            active: this.props.active,
            childrenNulled: false
        }
    }

    fetchSubNodes = (e) => {
        e.stopPropagation();
        //debugger;
        if (this.isExpanded()) {
            this.setExpanded(false);
        } else {
            if (this.state.subnodes.length > 0 || this.state.terminal || (this.isActive() === false)) {
                this.setExpanded(true);
                return
            }
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
    }

    setExpanded = (exp) => { this.props.changeCallback(this.props.node_id, {expanded: exp}) }

    onCheckboxChecked = (e) => {
        if (! this.isActive()) return;
        let checked = !this.isChecked();
        let changes = {checked: checked};

        this.props.changeCallback(this.props.node_id, changes);
    }

    isExpanded = () => this.props.data[this.props.node_id].expanded

    isChecked = () => this.props.data[this.props.node_id].checked

    isActive = () => {
        let active = this.props.data[this.props.node_id].active;
        if (active === undefined)
            return true;
        return active;
    }

    render() {
        let checkbox, description;
        if (this.props.checkable) {
            checkbox = <NodeCheckBox id={"chkbx-" + this.props.node_id}
                checked={this.isChecked()}
                onChange={this.onCheckboxChecked} active={this.isActive()} />
        }

        if (this.props.description) {
            description = <div className="rubrique-description" dangerouslySetInnerHTML={{__html: this.props.description}}></div>
        }

        return (
            <li key={this.props.node_id} onClick={this.fetchSubNodes} className={this.props._class}>
                {checkbox}{checkbox ? " " : ""}<label htmlFor={"chkbx-" + this.props.node_id}>
                {this.props.label}</label>&nbsp;
                {this.state.terminal ? "" : (this.isExpanded()  ? <span className="fas fa-chevron-down"></span> : <span className="fas fa-chevron-right"></span>) }
                {description}
                <ul key={'cont-' + this.props.node_id.toString()} className={this.isExpanded() ? "node-visible" : "node-hidden"}>
                    {this.state.subnodes.map(node => (
                        <TreeNode label={node.label} node_id={node.id} key={'node-' + node.id}
                            description={node.description}
                            checkable={node.checkable}
                            nullying={node.nullying}
                            data={this.props.data}
                            changeCallback={this.props.changeCallback}
                            addCallback={this.props.addCallback} />
                    ))}
                </ul>
            </li>
        );
    }
}