class RemarquableModal extends React.Component {
    // Dialogue pour l'édition des propriétés modales

    checkbox = function(name, label, icon) {
        var val; // $cont.find("input[name='" + name + "[]']").val();
        return <div className="checkbox"><label>
            <input type="checkbox" data-name={name + (val ? ' checked' : '')} />&nbsp;
            <span className={"fas fa-" + icon }> </span>&nbsp;{label}
            </label></div>
      }

    render() {
        return (
            <div className={`modal${this.props.show ? " show" : ""}`} id="remarquable-dialogue"
            style={{
                display: this.props.show ? 'block' : 'none',
              }}>
                <div className="modal-dialog">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h4>Elément remarquable : informations complémentaires</h4>
                            <button type="button" className="close" onClick={this.props.dismissModal} aria-label="Fermer">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div className="modal-body">
                            <form class="form-horizontal">
                                <p>Cet élément est intéressant d'un point de vue :</p>
                                {this.checkbox('interet_scientifique', 'scientifique', 'flask')}
                                {this.checkbox('interet_pedagogique', 'pédagogique', 'chalkboard-teacher')}
                                {this.checkbox('interet_esthetique', 'esthétique', 'image')}
                                {this.checkbox('interet_historique', 'historique/culturel', 'book')}
                                <br /><div class="form-group">
                                    <label>Commentaires :</label>
                                    <textarea name="remarquable_info" class="form-control">{}</textarea>
                                </div>
                            </form>
                        </div>
                        <div className="modal-footer">
                            <button type="buttun" className="btn btn-primary" onClick={this.props.saveContent}>Enregistrer</button>
                            <button type="button" className="btn btn-secondary" onClick={this.props.dismissModal}>Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}


class TreeView extends React.Component {

    constructor(props) {
        super(props);

        const nodes = {};
        nodes[props.node_id] = {
            active: true
        };
        this.state = {
            isLoaded: false,
            inTransaction: false,
            nodes: nodes,
            showModal: false
        };
    }

    componentDidMount() {
        // on récupère la siste des réponses
        $.get(site_url('api/get_responses_site/' + this.props.id), (data) => {
            this.setState({isLoaded: true, responses: data});
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
        let changed_data = {};
        changed_data[id] = data;

        elt = Object.assign(elt, data);

        if (elt.nullying && "checked" in data) {
            // désactivation des siblings sur élément nul
            let checked = data.checked;
            for (let i in this.getDescendants(elt.parent_id, {})) {
                if (i != id) {
                    if (checked) {
                        changed_data[i] = {checked: false}
                    }
                    nodes[i].active = !checked;
                }
            }
        }

        if (data.checked && ! elt.nullying) {
            elt.expanded = true;

            // propagation aux parents
            let asc = this.getAscendants(id, {});
            for (let i in asc) {
                if (asc[i].checkable) {
                    if (! this.getNodeData(i, 'checked')) {
                        changed_data[i] = {checked: true};
                    }
                }
            }
        }
        if (data.checked === false) {
            // décochage tous descendants
            let desc = this.getDescendants(id, {});
            for (let i in desc) {
                if (this.getNodeData(i, 'checked')) {
                    changed_data[i] = {checked: false}
                }
            }
        }

        nodes[id] = elt;

        // enregistrement des changements
        if (elt.checkable && 'checked' in data) {
            this.setState({inTransaction: true});

            $.post(site_url('Site/save_qcm/' + this.props.level + '/' + this.props.id), {
                data: JSON.stringify(changed_data)
            }, (response) => {
                if (response.success) {
                    this.setState({
                        inTransaction: false,
                        nodes: nodes,
                        responses: response.new_data
                    });
                }
            });
        } else {
            this.setState({nodes: nodes});
        }
    }

    addElement = (parent_id, data) => {
        let nodes = this.state.nodes;
        data.forEach(e => {
            if (e.id in this.state.responses) {
                e.checked = true;
            }
            nodes[e.id] = {
                parent_id: parent_id,
                checkable: e.checkable,
                expanded: e.expanded || false,
                active: true,
                nullying: e.nullying
            };
        });
        this.setState({nodes: nodes});
    }

    getNodeData = (node_id, attribute) => {
        const d = this.state.responses[node_id];
        if (attribute == 'checked')
            return d != undefined;
        if (d) {
            return d[attribute];
        }
    }

    render() {
        if (! this.state.isLoaded) {
            return <div>Chargement...</div>;
        } else {
            return (
                <div>
                    <TreeNode label={this.props.title} node_id={this.props.node_id}
                    data={this.state.nodes}
                    changeCallback={this.changeElement}
                    addCallback={this.addElement}
                    getNodeData={this.getNodeData}
                    openModalCallback={(e) => {e.preventDefault(); this.setState({showModal: true})}}
                    />
                    <RemarquableModal
                        show={this.state.showModal}
                        dismissModal={(e) => {e.preventDefault(); this.setState({showModal: false})}}
                    />
                </div>
            );
        }
    }
}