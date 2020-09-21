var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

// Composant affichant une carte Leaflet de base

var _window$ReactLeaflet = window.ReactLeaflet,
    LeafletMap = _window$ReactLeaflet.Map,
    TileLayer = _window$ReactLeaflet.TileLayer,
    WMSTileLayer = _window$ReactLeaflet.WMSTileLayer,
    DivOverlay = _window$ReactLeaflet.DivOverlay,
    LayersControl = _window$ReactLeaflet.LayersControl,
    Marker = _window$ReactLeaflet.Marker,
    Popup = _window$ReactLeaflet.Popup,
    GeoJSON = _window$ReactLeaflet.GeoJSON,
    Polygon = _window$ReactLeaflet.Polygon,
    withLeaflet = _window$ReactLeaflet.withLeaflet;

var BaseLayer = LayersControl.BaseLayer;

var ignOrthoUrl = "http://wxs.ign.fr/qi0jtcvtmn01lkt0621p5yci/wmts?" + "REQUEST=GetTile&SERVICE=WMTS&VERSION=1.0.0" + "&STYLE=normal" + "&TILEMATRIXSET=PM" + "&FORMAT=image/jpeg" + "&LAYER=ORTHOIMAGERY.ORTHOPHOTOS" + "&TILEMATRIX={z}" + "&TILEROW={y}" + "&TILECOL={x}";
var ignScanUrl = "http://wxs.ign.fr/qi0jtcvtmn01lkt0621p5yci/wmts?" + "REQUEST=GetTile&SERVICE=WMTS&VERSION=1.0.0" + "&STYLE=normal" + "&TILEMATRIXSET=PM" + "&FORMAT=image/jpeg" + "&LAYER=GEOGRAPHICALGRIDSYSTEMS.MAPS.SCAN-EXPRESS.STANDARD" + "&TILEMATRIX={z}" + "&TILEROW={y}" + "&TILECOL={x}";

function pointInPolygon(point, vs) {
    // adapted from https://github.com/substack/point-in-polygon/blob/master/index.js
    // ray-casting algorithm based on
    // http://www.ecse.rpi.edu/Homepages/wrf/Research/Short_Notes/pnpoly.html

    var x = point.lng,
        y = point.lat;

    var inside = false;
    for (var i = 0, j = vs.length - 1; i < vs.length; j = i++) {
        var xi = vs[i][0],
            yi = vs[i][1];
        var xj = vs[j][0],
            yj = vs[j][1];

        var intersect = yi > y != yj > y && x < (xj - xi) * (y - yi) / (yj - yi) + xi;
        if (intersect) inside = !inside;
    }

    return inside;
};

function pointInMultiPolygon(pt, geom) {
    // calcule si un point est inclus dans un polygone GeoJson

}

var _MapPopup = function (_DivOverlay) {
    _inherits(_MapPopup, _DivOverlay);

    function _MapPopup() {
        var _ref;

        var _temp, _this, _ret;

        _classCallCheck(this, _MapPopup);

        for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
            args[_key] = arguments[_key];
        }

        return _ret = (_temp = (_this = _possibleConstructorReturn(this, (_ref = _MapPopup.__proto__ || Object.getPrototypeOf(_MapPopup)).call.apply(_ref, [this].concat(args))), _this), _this.getGeolInfo = function (map, position, callback, errorCallback) {
            var url = site_url('carto/featureInfo');
            var size = map.getSize();
            var xy = map.latLngToLayerPoint(position);
            var params = {
                BBOX: map.getBounds().toBBoxString(),
                WIDTH: size.x,
                HEIGHT: size.y,
                X: Math.round(xy.x),
                Y: Math.round(xy.y),
                lat: position.lat,
                lng: position.lng
            };
            $.ajax(url, { data: params, success: function success(data) {
                    if (data && data.features) {
                        _this.polygon = data.features[0].geometry;
                        callback(data);
                    } else {
                        errorCallback();
                    }
                }, error: errorCallback });
        }, _temp), _possibleConstructorReturn(_this, _ret);
    }

    _createClass(_MapPopup, [{
        key: "createLeafletElement",

        // composant custom pour le popup des infos géologiques
        value: function createLeafletElement(props) {
            var popup = L.popup({ maxWidth: 200 });
            popup.setLatLng(L.latLng(props.position));
            return popup;
        }

        // téléchargement des infos BRGM sur un point cliqué

    }, {
        key: "updateLeafletElement",
        value: function updateLeafletElement(fromProps, toProps) {
            // TODO: eviter plusieurs requetes sur la même entité (vérifier si on est tjrs ds le polygone)
            if ("position" in toProps && !toProps.position.equals(fromProps.position)) {
                var map = toProps.leaflet.map;
                if (map.getZoom() < 11) return false;
                var popup = this.leafletElement;
                var onDataFetch = this.props.onDataFetch;
                popup.setContent("Chargement...").setLatLng(toProps.position).openOn(map);
                this.getGeolInfo(map, toProps.position, function (data) {
                    if (data.features.length > 0) {
                        var props = data.features[0].properties;
                        var age = props.label_age_deb + (props.label_age_fin ? ' - ' + props.label_age_fin : '');
                        var cont = "<p><b>Entit\xE9 g\xE9ologique :</b><br />" + props.descr + "</p>\n                        <table class=\"table table-sm\">\n                            <tr><th>Appellation locale</th><td>" + props.ap_locale + "</td></tr>\n                            <tr><th>Notation sur la carte</td><td>" + props.notation + "</td></tr>\n                            <tr><th>Type de formation g\xE9ologique</th><td>" + props.type_geol + "</td></tr>\n                            <tr><th>Age des roches</th><td>" + age + "</td></tr>\n                            <tr><th>Lithologie</th><td>" + props.lithologie + "</td></tr>\n                            <tr><th>G\xE9ochimie</th><td>" + props.geochimie + "</td></tr>\n                        </table>";
                        popup.setContent(cont);
                    }
                    onDataFetch(data);
                }, function () {
                    popup.setContent("Erreur de requête !");
                });
            }
        }
    }]);

    return _MapPopup;
}(DivOverlay);

var MapPopup = withLeaflet(_MapPopup);

// Composant permettant d'afficher la position sur l'échelle stratigraphique

var BRGMScale = function (_React$Component) {
    _inherits(BRGMScale, _React$Component);

    function BRGMScale(props) {
        _classCallCheck(this, BRGMScale);

        var _this2 = _possibleConstructorReturn(this, (BRGMScale.__proto__ || Object.getPrototypeOf(BRGMScale)).call(this, props));

        _this2.state = {
            indicatorPosition: 0,
            indicatorHeight: 5
        };
        _this2.originalWidth = 2624;

        _this2.onImgLoad = function (e) {
            // recupère la largeur effective de l'image
            _this2.setState({ imgWidth: e.target.offsetWidth });
            _this2.contRef.current.scrollTop = 394 * _this2.state.imgWidth / _this2.originalWidth;
        };

        _this2.contRef = React.createRef();
        return _this2;
    }

    _createClass(BRGMScale, [{
        key: "componentDidUpdate",
        value: function componentDidUpdate() {
            // Ajuste l'affichage de l'image sur le marqueur
            if (this.props.indicatorPosition && this.props.indicatorPosition.min) {
                var factor = this.state.imgWidth / this.originalWidth;
                var hgt = (this.props.indicatorPosition.max - this.props.indicatorPosition.min) * factor + 2;
                var containerOffset = this.props.indicatorPosition.min * factor - 100 + hgt / 2;
                this.contRef.current.scrollTop = containerOffset;
            }
        }
    }, {
        key: "render",
        value: function render() {
            var indicatorStyle = {
                display: "none"
            };
            if (this.props.indicatorPosition && this.props.indicatorPosition.min) {
                // placement du marqueur
                var factor = this.state.imgWidth / this.originalWidth;
                var hgt = (this.props.indicatorPosition.max - this.props.indicatorPosition.min) * factor + 2;
                indicatorStyle = {
                    top: this.props.indicatorPosition.min * factor,
                    height: hgt,
                    display: "block"
                };
            }

            return React.createElement(
                "div",
                { className: "strat-scale-frame", ref: this.contRef },
                React.createElement(
                    "div",
                    { className: "strat-scale-container" },
                    React.createElement("div", { className: "strat-scale-indicator", style: indicatorStyle }),
                    React.createElement("img", { onLoad: this.onImgLoad, src: base_url + "resources/images/echelle_brgm.png" })
                )
            );
        }
    }]);

    return BRGMScale;
}(React.Component);

var GeologyMap = function (_React$Component2) {
    _inherits(GeologyMap, _React$Component2);

    function GeologyMap() {
        var _ref2;

        var _temp2, _this3, _ret2;

        _classCallCheck(this, GeologyMap);

        for (var _len2 = arguments.length, args = Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
            args[_key2] = arguments[_key2];
        }

        return _ret2 = (_temp2 = (_this3 = _possibleConstructorReturn(this, (_ref2 = GeologyMap.__proto__ || Object.getPrototypeOf(GeologyMap)).call.apply(_ref2, [this].concat(args))), _this3), _this3.state = {
            infoPaneActivated: true,
            clickPosition: L.latLng(48.2, 0.3),
            center: [48.2, 0.3],
            geolPg: []
        }, _this3.onClick = function (e) {
            // Affichage popup infos geol
            if (!_this3.props.geolInfoActivated) return;
            _this3.setState({ clickPosition: e.latlng });
        }, _this3.onSiteAdded = function (e) {
            var bounds = e.target.getBounds();
            _this3.setState({ mapBounds: bounds });
        }, _this3.onPopupDataFetched = function (data) {
            var ft = data.features[0];
            var coords = ft.geometry.coordinates.map(function (p) {
                return p.map(function (c) {
                    return c.reverse();
                });
            });
            _this3.setState({
                geolPg: coords,
                indicatorPosition: {
                    min: ft.properties.pix_min_fin ? ft.properties.pix_min_fin : ft.properties.pix_min_deb,
                    max: ft.properties.pix_max_deb
                }
            });
        }, _this3.polygonStyle = function () {
            return {
                color: "green",
                width: 4,
                fill: false
            };
        }, _temp2), _possibleConstructorReturn(_this3, _ret2);
    }

    _createClass(GeologyMap, [{
        key: "render",
        value: function render() {
            var geolPopup = void 0,
                geolPg = void 0;
            if (this.props.geolInfoActivated) {
                geolPopup = React.createElement(MapPopup, { position: this.state.clickPosition, onDataFetch: this.onPopupDataFetched });
                geolPg = React.createElement(Polygon, { positions: this.state.geolPg });
            }

            return React.createElement(
                "div",
                { id: "map-container" },
                React.createElement(
                    LeafletMap,
                    { bounds: this.state.mapBounds, center: [48.2, 0.3], zoom: 12, onClick: this.onClick },
                    geolPopup,
                    React.createElement(
                        LayersControl,
                        { position: "topright" },
                        React.createElement(
                            BaseLayer,
                            { name: "Orthophotos IGN" },
                            React.createElement(TileLayer, { url: ignOrthoUrl, attribution: "IGN-F/Geoportail",
                                maxZoom: "18", tileSize: "256" })
                        ),
                        React.createElement(
                            BaseLayer,
                            { name: "Scan Express IGN" },
                            React.createElement(TileLayer, { url: ignScanUrl, attribution: "IGN-F/Geoportail",
                                maxZoom: "18", tileSize: "256" })
                        ),
                        React.createElement(
                            BaseLayer,
                            { name: "Carte g\xE9ologique harmonis\xE9e" },
                            React.createElement(WMSTileLayer, { url: "http://geoservices.brgm.fr/geologie", attribution: "\xA9 BRGM",
                                layers: "SCAN_H_GEOL50",
                                format: "image/jpeg",
                                maxZoom: "15" })
                        ),
                        React.createElement(
                            BaseLayer,
                            { checked: true, name: "Carte g\xE9ologique scann\xE9e" },
                            React.createElement(WMSTileLayer, { url: "http://geoservices.brgm.fr/geologie", attribution: "\xA9 BRGM",
                                layers: "GEOLOGIE",
                                format: "image/jpeg",
                                maxZoom: "15" })
                        )
                    ),
                    React.createElement(GeoJSON, { data: this.props.siteGeom, style: this.polygonStyle, onAdd: this.onSiteAdded }),
                    geolPg
                ),
                React.createElement(BRGMScale, { indicatorPosition: this.state.indicatorPosition })
            );
        }
    }]);

    return GeologyMap;
}(React.Component);

GeologyMap.defaultProps = { geolInfoActivated: true };