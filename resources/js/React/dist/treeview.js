var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var RemarquableModal = function (_React$Component) {
    _inherits(RemarquableModal, _React$Component);

    function RemarquableModal() {
        var _ref;

        var _temp, _this, _ret;

        _classCallCheck(this, RemarquableModal);

        for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
            args[_key] = arguments[_key];
        }

        return _ret = (_temp = (_this = _possibleConstructorReturn(this, (_ref = RemarquableModal.__proto__ || Object.getPrototypeOf(RemarquableModal)).call.apply(_ref, [this].concat(args))), _this), _this.checkbox = function (name, label, icon) {
            var val; // $cont.find("input[name='" + name + "[]']").val();
            return React.createElement(
                "div",
                { className: "checkbox" },
                React.createElement(
                    "label",
                    null,
                    React.createElement("input", { type: "checkbox", "data-name": name + (val ? ' checked' : '') }),
                    "\xA0",
                    React.createElement(
                        "span",
                        { className: "fas fa-" + icon },
                        " "
                    ),
                    "\xA0",
                    label
                )
            );
        }, _temp), _possibleConstructorReturn(_this, _ret);
    }
    // Dialogue pour l'édition des propriétés modales

    _createClass(RemarquableModal, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { className: "modal" + (this.props.show ? " show" : ""), id: "remarquable-dialogue",
                    style: {
                        display: this.props.show ? 'block' : 'none'
                    } },
                React.createElement(
                    "div",
                    { className: "modal-dialog" },
                    React.createElement(
                        "div",
                        { className: "modal-content" },
                        React.createElement(
                            "div",
                            { className: "modal-header" },
                            React.createElement(
                                "h4",
                                null,
                                "El\xE9ment remarquable : informations compl\xE9mentaires"
                            ),
                            React.createElement(
                                "button",
                                { type: "button", className: "close", onClick: this.props.dismissModal, "aria-label": "Fermer" },
                                React.createElement(
                                    "span",
                                    { "aria-hidden": "true" },
                                    "\xD7"
                                )
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "modal-body" },
                            React.createElement(
                                "form",
                                { "class": "form-horizontal" },
                                React.createElement(
                                    "p",
                                    null,
                                    "Cet \xE9l\xE9ment est int\xE9ressant d'un point de vue :"
                                ),
                                this.checkbox('interet_scientifique', 'scientifique', 'flask'),
                                this.checkbox('interet_pedagogique', 'pédagogique', 'chalkboard-teacher'),
                                this.checkbox('interet_esthetique', 'esthétique', 'image'),
                                this.checkbox('interet_historique', 'historique/culturel', 'book'),
                                React.createElement("br", null),
                                React.createElement(
                                    "div",
                                    { "class": "form-group" },
                                    React.createElement(
                                        "label",
                                        null,
                                        "Commentaires :"
                                    ),
                                    React.createElement("textarea", { name: "remarquable_info", "class": "form-control" })
                                )
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "modal-footer" },
                            React.createElement(
                                "button",
                                { type: "buttun", className: "btn btn-primary", onClick: this.props.saveContent },
                                "Enregistrer"
                            ),
                            React.createElement(
                                "button",
                                { type: "button", className: "btn btn-secondary", onClick: this.props.dismissModal },
                                "Fermer"
                            )
                        )
                    )
                )
            );
        }
    }]);

    return RemarquableModal;
}(React.Component);

var TreeView = function (_React$Component2) {
    _inherits(TreeView, _React$Component2);

    function TreeView(props) {
        _classCallCheck(this, TreeView);

        var _this2 = _possibleConstructorReturn(this, (TreeView.__proto__ || Object.getPrototypeOf(TreeView)).call(this, props));

        _initialiseProps.call(_this2);

        var nodes = {};
        nodes[props.node_id] = {
            active: true
        };
        _this2.state = {
            isLoaded: false,
            inTransaction: false,
            nodes: nodes,
            showModal: false
        };
        return _this2;
    }

    _createClass(TreeView, [{
        key: "componentDidMount",
        value: function componentDidMount() {
            var _this3 = this;

            // on récupère la siste des réponses
            $.get(site_url('api/get_responses_site/' + this.props.id), function (data) {
                _this3.setState({ isLoaded: true, responses: data });
            });
        }
    }, {
        key: "render",
        value: function render() {
            var _this4 = this;

            if (!this.state.isLoaded) {
                return React.createElement(
                    "div",
                    null,
                    "Chargement..."
                );
            } else {
                return React.createElement(
                    "div",
                    null,
                    React.createElement(TreeNode, { label: this.props.title, node_id: this.props.node_id,
                        data: this.state.nodes,
                        changeCallback: this.changeElement,
                        addCallback: this.addElement,
                        getNodeData: this.getNodeData,
                        openModalCallback: function openModalCallback(e) {
                            e.preventDefault();_this4.setState({ showModal: true });
                        }
                    }),
                    React.createElement(RemarquableModal, {
                        show: this.state.showModal,
                        dismissModal: function dismissModal(e) {
                            e.preventDefault();_this4.setState({ showModal: false });
                        }
                    })
                );
            }
        }
    }]);

    return TreeView;
}(React.Component);

var _initialiseProps = function _initialiseProps() {
    var _this5 = this;

    this.getChildren = function (parent_id) {
        var rep = {};
        for (var id in _this5.state.nodes) {
            if (_this5.state.nodes[id].parent_id == parent_id) rep[id] = _this5.state.nodes[id];
        }
        return rep;
    };

    this.getDescendants = function (parent_id, to_append) {
        for (var id in _this5.state.nodes) {
            if (_this5.state.nodes[id].parent_id == parent_id) {
                to_append[id] = _this5.state.nodes[id];
                to_append = _this5.getDescendants(id, to_append);
            }
        }
        return to_append;
    };

    this.getAscendants = function (id, to_append) {
        var parent_id = _this5.state.nodes[id].parent_id;
        if (parent_id in _this5.state.nodes) {
            to_append[parent_id] = _this5.state.nodes[parent_id];
            to_append = _this5.getAscendants(parent_id, to_append);
        }
        return to_append;
    };

    this.changeElement = function (id, data) {
        var nodes = _this5.state.nodes;
        var elt = nodes[id];
        var changed_data = {};
        changed_data[id] = data;

        elt = Object.assign(elt, data);

        if (elt.nullying && "checked" in data) {
            // désactivation des siblings sur élément nul
            var checked = data.checked;
            for (var i in _this5.getDescendants(elt.parent_id, {})) {
                if (i != id) {
                    if (checked) {
                        changed_data[i] = { checked: false };
                    }
                    nodes[i].active = !checked;
                }
            }
        }

        if (data.checked && !elt.nullying) {
            elt.expanded = true;

            // propagation aux parents
            var asc = _this5.getAscendants(id, {});
            for (var _i in asc) {
                if (asc[_i].checkable) {
                    if (!_this5.getNodeData(_i, 'checked')) {
                        changed_data[_i] = { checked: true };
                    }
                }
            }
        }
        if (data.checked === false) {
            // décochage tous descendants
            var desc = _this5.getDescendants(id, {});
            for (var _i2 in desc) {
                if (_this5.getNodeData(_i2, 'checked')) {
                    changed_data[_i2] = { checked: false };
                }
            }
        }

        nodes[id] = elt;

        // enregistrement des changements
        if (elt.checkable && 'checked' in data) {
            _this5.setState({ inTransaction: true });

            $.post(site_url('Site/save_qcm/' + _this5.props.level + '/' + _this5.props.id), {
                data: JSON.stringify(changed_data)
            }, function (response) {
                if (response.success) {
                    _this5.setState({
                        inTransaction: false,
                        nodes: nodes,
                        responses: response.new_data
                    });
                }
            });
        } else {
            _this5.setState({ nodes: nodes });
        }
    };

    this.addElement = function (parent_id, data) {
        var nodes = _this5.state.nodes;
        data.forEach(function (e) {
            if (e.id in _this5.state.responses) {
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
        _this5.setState({ nodes: nodes });
    };

    this.getNodeData = function (node_id, attribute) {
        var d = _this5.state.responses[node_id];
        if (attribute == 'checked') return d != undefined;
        if (d) {
            return d[attribute];
        }
    };
};