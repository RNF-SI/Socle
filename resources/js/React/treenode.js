function NodeCheckBox(props) {
    return <input type="checkbox" checked={props.checked} onChange={props.onChange} />
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
            checked: props.node_id in responses,
            terminal: false
        }
    }

    fetchSubNodes = (e) => {
        e.stopPropagation();
        if (this.state.expanded) {
            this.setState({expanded: false});
        } else {
            if (this.state.subnodes.length > 0 || this.state.terminal) {
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

    onCheckboxChecked = (e) => {
        this.state.checked = ! this.state.checked;
        // TODO: enregistrement des changements
    }

    onNullCheckboxChecked = (e) => {
        // qd on coche la boite non concerné dans les sous-items
    }

    render() {
        let checkbox, nullNode;
        if (this.props.checkable) {
            checkbox = <NodeCheckBox checked={this.state.checked} onChange={this.onCheckboxChecked} />
        }
        if (this.props.nullable) {
            nullNode = <NodeNull checked={false} />
        }


        return (
            <li key={this.props.node_id} onClick={this.fetchSubNodes} className={this.props._class}>
                <label>{checkbox}{checkbox ? " " : ""}
                {this.props.label}</label>
                <ul key={'cont-' + this.props.node_id.toString()} className={this.state.expanded ? "node-visible" : "node-hidden"}>
                    {this.state.subnodes.map(node => (
                        <TreeNode label={node.label} node_id={node.id} key={'node-' + node.id} checkable={node.checkable} nullable={node.nullable}  />
                    ))}
                </ul>
            </li>
        );
    }
}