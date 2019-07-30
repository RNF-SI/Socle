

class TreeView extends React.Component {
    responses = {}

    constructor(props) {
        super(props);

        const nodes = {};
        nodes[props.node_id] = {
            active: true
        };
        this.state = {
            isLoaded: false,
            inTransaction: false,
            nodes: nodes
        };
    }

    componentDidMount() {
        $.get(site_url('api/get_responses_site/' + this.props.id), (data) => {
            this.responses = data;
            this.setState({isLoaded: true});
        });
    }


    getChildren = (parent_id) => {
        let rep = {};
        for (let id in this.state.nodes) {
            if (this.state.nodes[id].parent_id == parent_id)
                rep[id] = this.state.nodes[id];
        }
        return rep;
    }

    getDescendants = (parent_id, to_append) => {
        for (let id in this.state.nodes) {
            if (this.state.nodes[id].parent_id == parent_id) {
                to_append[id] = this.state.nodes[id];
                to_append = this.getDescendants(id, to_append);
            }
        }
        return to_append;
    }

    getAscendants = (id, to_append) => {
        let parent_id = this.state.nodes[id].parent_id;
        if (parent_id in this.state.nodes) {
            to_append[parent_id] = this.state.nodes[parent_id];
            to_append = this.getAscendants(parent_id, to_append);
        }
        return to_append;
    }

    changeElement = (id, data) => {
        let nodes = this.state.nodes;
        let elt = nodes[id];
        let changed_ids = [id];

        elt = Object.assign(elt, data);

        if (elt.nullying && "checked" in data) {
            let checked = data.checked;
            for (let i in this.getDescendants(elt.parent_id, {})) {
                if (i != id) {
                    if (checked) nodes[i].checked = false;
                    nodes[i].active = !checked;
                    changed_ids.push(i);
                }
            }
        }

        if (data.checked && ! elt.nullying) {
            elt.expanded = true;

            // propagation aux parents
            let asc = this.getAscendants(id, {});
            for (let i in asc) {
                if (asc[i].checkable) {
                    if (! asc[i].checked) changed_ids.push(i);
                    asc[i].checked = true;
                    nodes[i] = asc[i];
                }
            }
        }
        if (data.checked === false) {
            // décochage tous descendants
            let desc = this.getDescendants(id, {});
            for (let i in desc) {
                if (desc[i].checked) changed_ids.push(i);
                desc[i].checked = false;
                nodes[i] = desc[i];
            }
        }

        nodes[id] = elt;

        // enregistrement des changements
        if (elt.checkable && 'checked' in data) {
            this.setState({inTransaction: true});
            let post_data = {};
            changed_ids.forEach(i => post_data[i] = nodes[i])
            $.post(site_url('Site/save_qcm/' + this.props.level + '/' + this.props.id), {
                data: JSON.stringify(post_data)
            }, (response) => {
                this.setState({inTransaction: false, nodes: nodes});
            });
        } else {
            this.setState({nodes: nodes});
        }
    }

    addElement = (parent_id, data) => {
        let nodes = this.state.nodes;
        data.forEach(e => {
            if (e.id in this.responses) {
                e.checked = true;
            }
            nodes[e.id] = {
                parent_id: parent_id,
                checked: e.checked || false,
                checkable: e.checkable,
                expanded: e.expanded || false,
                active: true,
                nullying: e.nullying
            };
        });
        this.setState({nodes: nodes});
    }

    render() {
        if (! this.state.isLoaded) {
            return <div>Chargement...</div>;
        } else {
            return (
                <TreeNode label={this.props.title} node_id={this.props.node_id} data={this.state.nodes}
                changeCallback={this.changeElement}
                addCallback={this.addElement} />
            );
        }
    }
}