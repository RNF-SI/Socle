var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

function NodeCheckBox(props) {
    return React.createElement("input", { type: "checkbox", id: props.id, checked: props.checked, onChange: props.onChange, disabled: props.active === false });
}

function NodeNull(props) {
    return React.createElement(
        "li",
        null,
        React.createElement(NodeCheckBox, { checked: props.checked, onChange: props.onChange }),
        "Non concern\xE9"
    );
}

// Composant permettant d'ajouter un item de QCM

var NewNode = function (_React$Component) {
    _inherits(NewNode, _React$Component);

    function NewNode(props) {
        _classCallCheck(this, NewNode);

        var _this = _possibleConstructorReturn(this, (NewNode.__proto__ || Object.getPrototypeOf(NewNode)).call(this, props));

        _this.onClickSave = function (e) {
            e.preventDefault();

            var url = site_url("api/create_qcm_item/" + _this.props.parent_id);
            $.post(url, { label: _this.state.label }, function (resp) {
                if (resp.success) {
                    _this.setState({ label: "", open: false });
                    _this.props.reloadItems();
                }
            });
        };

        _this.onClickOpen = function (e) {
            e.preventDefault();
            _this.setState({ open: !_this.state.open });
        };

        _this.handleChange = function (e) {
            _this.setState({ label: e.target.value });
        };

        _this.state = {
            open: false,
            label: ""
        };
        return _this;
    }

    _createClass(NewNode, [{
        key: "render",
        value: function render() {
            var inputWidget = React.createElement(
                "div",
                null,
                React.createElement("input", { type: "text", placeholder: "Intitul\xE9 de l'item", value: this.state.label, onChange: this.handleChange }),
                "\xA0",
                React.createElement(
                    "a",
                    { href: "#", onClick: this.onClickSave },
                    React.createElement("span", { className: "fas fa-save" })
                )
            );

            return React.createElement(
                "li",
                { className: "tree-add-item", onClick: function onClick(e) {
                        e.stopPropagation();
                    } },
                React.createElement(
                    "a",
                    { href: "#", onClick: this.onClickOpen },
                    React.createElement(
                        "span",
                        { className: this.state.open ? "fas fa-minus-circle" : "fas fa-plus-circle" },
                        " "
                    )
                ),
                "\xA0",
                this.state.open ? inputWidget : "Ajouter un item..."
            );
        }
    }]);

    return NewNode;
}(React.Component);

var TreeNode = function (_React$Component2) {
    _inherits(TreeNode, _React$Component2);

    function TreeNode(props) {
        _classCallCheck(this, TreeNode);

        var _this2 = _possibleConstructorReturn(this, (TreeNode.__proto__ || Object.getPrototypeOf(TreeNode)).call(this, props));

        _this2.getData = function (prop) {
            return _this2.props.getNodeData(_this2.props.node_id, prop);
        };

        _this2.fetchSubNodes = function () {
            _this2.setState({ loadingSubnodes: true });
            var url = site_url('api/get_child_nodes/' + _this2.props.node_id);
            $.get(url, function (data) {
                _this2.props.addCallback(_this2.props.node_id, data);
                _this2.setState({
                    subnodes: data,
                    loadingSubnodes: false,
                    terminal: data.length == 0
                });
                _this2.setExpanded(true);
            });
        };

        _this2.onClickExpand = function (e) {
            if (e) e.stopPropagation();
            if (_this2.isExpanded()) {
                _this2.setExpanded(false);
            } else {
                if (_this2.state.subnodes.length > 0 || _this2.state.terminal || _this2.isActive() === false) {
                    _this2.setExpanded(true);
                    return;
                }
                _this2.fetchSubNodes();
            }
        };

        _this2.setExpanded = function (exp) {
            _this2.props.changeCallback(_this2.props.node_id, { expanded: exp });
        };

        _this2.onCheckboxChecked = function (e) {
            e.stopPropagation();
            if (!_this2.isActive()) return;
            var checked = !_this2.isChecked();
            var changes = { checked: checked };
            _this2.onClickExpand();
            _this2.props.changeCallback(_this2.props.node_id, changes);
        };

        _this2.onRemarquableClick = function (e) {
            e.preventDefault();
            var changes = { checked: true, remarquable: !_this2.isRemarquable() };

            _this2.props.changeCallback(_this2.props.node_id, changes);
        };

        _this2.isExpanded = function () {
            return _this2.props.data[_this2.props.node_id].expanded;
        };

        _this2.isChecked = function () {
            return _this2.getData('checked');
        };

        _this2.isActive = function () {
            var active = _this2.props.data[_this2.props.node_id].active;
            if (active === undefined) return true;
            return active;
        };

        _this2.isRemarquable = function () {
            return _this2.isChecked() && _this2.getData('remarquable');
        };

        _this2.state = {
            subnodes: [],
            loadingSubnodes: false,
            expanded: false,
            terminal: false,
            childrenNulled: false
        };
        return _this2;
    }

    _createClass(TreeNode, [{
        key: "render",
        value: function render() {
            var _this3 = this;

            var checkbox = void 0,
                description = void 0,
                definition = void 0,
                newItemNode = void 0,
                remarquable = void 0;

            if (this.props.checkable) {
                checkbox = React.createElement(NodeCheckBox, { id: "chkbx-" + this.props.node_id,
                    checked: this.isChecked(),
                    onChange: this.onCheckboxChecked, active: this.isActive() });
            }

            if (this.isChecked() && !this.props.nullying) {
                var buttonEditRem = void 0;
                if (this.isRemarquable()) buttonEditRem = React.createElement(
                    "a",
                    { className: "remarquable-edit",
                        onClick: this.props.openModalCallback, href: "#", title: "crit\xE8res de remarquabilit\xE9" },
                    React.createElement(
                        "span",
                        { className: "fas fa-edit" },
                        "\xA0"
                    )
                );
                remarquable = React.createElement(
                    "span",
                    { className: "remarquable-control " + (this.isRemarquable() ? "remarquable" : "") },
                    React.createElement(
                        "a",
                        { className: "coche-remarquable", href: "#", title: "Signaler cet \xE9l\xE9ment comme remarquable",
                            onClick: this.onRemarquableClick },
                        "\u2605\xA0"
                    ),
                    buttonEditRem
                );
            }

            if (this.props.description) {
                description = React.createElement("div", { className: "rubrique-description", dangerouslySetInnerHTML: { __html: this.props.description } });
            }

            if (this.props.definition) {
                definition = React.createElement(
                    "span",
                    { className: "description-tooltip", "data-toggle": "popover", "data-content": this.props.definition },
                    "?"
                );
            }

            if (this.state.subnodes.length > 0 && this.props.checkable) {
                newItemNode = React.createElement(NewNode, { parent_id: this.props.node_id, reloadItems: this.fetchSubNodes });
            }

            activate_popover("body");

            return React.createElement(
                "li",
                { key: this.props.node_id, className: this.props._class },
                checkbox,
                checkbox ? " " : "",
                React.createElement(
                    "span",
                    { className: "tree-item-label", onClick: this.onClickExpand },
                    this.props.label,
                    definition,
                    "\xA0",
                    remarquable,
                    "\xA0",
                    this.state.terminal ? "" : React.createElement("span", { className: this.isExpanded() ? "fas fa-chevron-down" : "fas fa-chevron-right" })
                ),
                description,
                React.createElement(
                    "ul",
                    { key: 'cont-' + this.props.node_id.toString(), className: this.isExpanded() ? "node-visible" : "node-hidden" },
                    this.state.subnodes.map(function (node) {
                        return React.createElement(TreeNode, { label: node.label, node_id: node.id, key: 'node-' + node.id,
                            className: node.class,
                            description: node.description,
                            definition: node.definition,
                            checkable: node.checkable,
                            nullying: node.nullying,
                            data: _this3.props.data,
                            getNodeData: _this3.props.getNodeData,
                            changeCallback: _this3.props.changeCallback,
                            addCallback: _this3.props.addCallback,
                            openModalCallback: _this3.props.openModalCallback });
                    }),
                    newItemNode
                )
            );
        }
    }]);

    return TreeNode;
}(React.Component);