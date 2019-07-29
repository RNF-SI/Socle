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
            checked: props.node_id in responses && ( props.parentChecked ),
            terminal: false,
            active: this.props.active,
            childrenNulled: false
        }
    }

    fetchSubNodes = (e) => {
        e.stopPropagation();
        if (this.state.expanded) {
            this.setState({expanded: false});
        } else {
            if (this.state.subnodes.length > 0 || this.state.terminal || (this.state.active === false)) {
                this.setState({expanded: true});
                return
            }
            this.setState({loadingSubnodes: true});
            const url = site_url('api/get_child_nodes/' + this.props.node_id);
            $.get(url, (data) => {
                this.setState({
                    subnodes: data,
                    loadingSubnodes: false,
                    expanded: true,
                    terminal: data.length == 0
                });
            })
        }
    }

    getChildren = (toAppend, subnodes) => {
        subnodes.forEach((elt) => {
            toAppend.push(elt);
            this.getChildren(toAppend, elt.subnodes)
        })
    }

    onChildChecked = () => {
        this.setState({checked: true});
        this.props.onChecked();
    }

    onNullChildChecked = (rep) => {
        this.setState({childrenNulled: rep});
    }

    onCheckboxChecked = (e) => {
        if (! this.state.active) return;
        var checked = !this.state.checked;
        this.setState({checked: checked});
        if (checked && this.props.onChecked && ! this.props.nullying) { // propagation du cochage enfant -> parent
            this.props.onChecked();
        }
        if (this.props.nullying && this.props.onNullChecked) {
            // propagates unchecked to siblings
            this.props.onNullChecked(checked);
        }
        if (checked) {
            this.setState({expanded: true});
        }

        // TODO: enregistrement des changements
    }

    render() {
        let checkbox, description;
        if (this.props.checkable) {
            checkbox = <NodeCheckBox id={"chkbx-" + this.props.node_id} checked={this.state.checked} onChange={this.onCheckboxChecked} active={this.props.active} />
        }

        if (this.props.description) {
            description = <div className="rubrique-description" dangerouslySetInnerHTML={{__html: this.props.description}}></div>
        }

        return (
            <li key={this.props.node_id} onClick={this.fetchSubNodes} className={this.props._class}>
                {checkbox}{checkbox ? " " : ""}<label htmlFor={"chkbx-" + this.props.node_id}>
                {this.props.label}</label>&nbsp;
                {this.state.terminal ? "" : (this.state.expanded  ? <span className="fas fa-chevron-down"></span> : <span className="fas fa-chevron-right"></span>) }
                {description}
                <ul key={'cont-' + this.props.node_id.toString()} className={this.state.expanded ? "node-visible" : "node-hidden"}>
                    {this.state.subnodes.map(node => (
                        <TreeNode label={node.label} node_id={node.id} key={'node-' + node.id}
                            description={node.description}
                            checkable={node.checkable} parentChecked={this.state.checked}
                            onChecked={this.onChildChecked} active={!this.state.childrenNulled || node.nullying}
                            nullying={node.nullying}
                            onNullChecked={this.onNullChildChecked} />
                    ))}
                </ul>
            </li>
        );
    }
}