// Composant affichant une carte Leaflet de base

const {Map: LeafletMap, TileLayer, WMSTileLayer,
    LayersControl, Marker, Popup, DivOverlay, GeoJSON, Polygon,
    withLeaflet } = window.ReactLeaflet;
const BaseLayer = LayersControl.BaseLayer;

const ignOrthoUrl = "http://wxs.ign.fr/qi0jtcvtmn01lkt0621p5yci/wmts?" +
        "REQUEST=GetTile&SERVICE=WMTS&VERSION=1.0.0" +
        "&STYLE=normal" +
        "&TILEMATRIXSET=PM" +
        "&FORMAT=image/jpeg"+
        "&LAYER=ORTHOIMAGERY.ORTHOPHOTOS" +
        "&TILEMATRIX={z}" +
        "&TILEROW={y}" +
        "&TILECOL={x}";
const ignScanUrl = "http://wxs.ign.fr/qi0jtcvtmn01lkt0621p5yci/wmts?" +
    "REQUEST=GetTile&SERVICE=WMTS&VERSION=1.0.0" +
    "&STYLE=normal" +
    "&TILEMATRIXSET=PM" +
    "&FORMAT=image/jpeg"+
    "&LAYER=GEOGRAPHICALGRIDSYSTEMS.MAPS.SCAN-EXPRESS.STANDARD"+
    "&TILEMATRIX={z}" +
    "&TILEROW={y}" +
    "&TILECOL={x}";


class _MapPopup extends DivOverlay {
    // composant custom pour le popup des infos géologiques
    createLeafletElement(props) {
        const popup = L.popup({maxWidth: 200});
        popup.setLatLng(L.latLng(props.position));
        return popup;
    }

    // téléchargement des infos BRGM sur un point cliqué
    getGeolInfo = (map, position, callback, errorCallback) => {
        const url = site_url('carto/featureInfoProxy');
        const size = map.getSize();
        const xy = map.latLngToLayerPoint(position);
        const params = {
            BBOX: map.getBounds().toBBoxString(),
            WIDTH: size.x,
            HEIGHT: size.y,
            X: Math.round(xy.x),
            Y: Math.round(xy.y),
            lat: position.lat,
            lng: position.lng
        };
        $.ajax(url, {data: params, success: (data) => {
            if (data.features) {
                callback(data);
            } else {
                errorCallback();
            }

        }, error: errorCallback});
    }

    updateLeafletElement(fromProps, toProps) {
        if ("position" in toProps && ! toProps.position.equals(fromProps.position)) {
            const map = toProps.leaflet.map;
            if (map.getZoom() < 11) return false;
            var popup = this.leafletElement;
            var onDataFetch = this.props.onDataFetch;
            popup.setContent("Chargement...").setLatLng(toProps.position).openOn(map);
            this.getGeolInfo(map, toProps.position, function(data) {
                if (data.features.length > 0) {
                    const props = data.features[0].properties;
                    const cont = `<p><b>Entité géologique :</b><br />${props.descr}</p>
                        <table class="table table-sm">
                            <tr><th>Appellation locale</th><td>${props.ap_locale}</td></tr>
                            <tr><th>Notation sur la carte</td><td>${props.notation}</td></tr>
                            <tr><th>Type de formation géologique</th><td>${props.type_geol}</td></tr>
                            <tr><th>Age des roches</th><td>${props.label}</td></tr>
                            <tr><th>Lithologie</th><td>${props.lithologie}</td></tr>
                            <tr><th>Géochimie</th><td>${props.geochimie}</td></tr>
                        </table>`;
                    popup.setContent(cont);
                }
                onDataFetch(data);
            }, () => { popup.setContent("Erreur de requête !") });
        }
    }
}

const MapPopup = withLeaflet(_MapPopup);

// Composant permettant d'afficher la position sur l'échelle stratigraphique
class BRGMScale extends React.Component {
    state = {
        indicatorPosition: 0,
        indicatorHeight: 5
    }

    constructor(props) {
        super(props);

        this.contRef = React.createRef();
    }

    componentDidUpdate() {
        // Ajuste l'affichage de l'image sur le marqueur
        if (this.props.indicatorPosition && this.props.indicatorPosition.min) {
            const factor = this.state.imgWidth / this.originalWidth;
            const hgt = (this.props.indicatorPosition.max - this.props.indicatorPosition.min) * factor + 2;
            const containerOffset = this.props.indicatorPosition.min * factor - 100 + hgt / 2;
            this.contRef.current.scrollTop = containerOffset
        }
    }

    originalWidth = 2624

    onImgLoad = (e) => {
        // recupère la largeur effective de l'image
        this.setState({imgWidth: e.target.offsetWidth})
    }

    render() {
        let indicatorStyle = {
            display: "none"
        };
        if (this.props.indicatorPosition && this.props.indicatorPosition.min) {
            // placement du marqueur
            const factor = this.state.imgWidth / this.originalWidth;
            const hgt = (this.props.indicatorPosition.max - this.props.indicatorPosition.min) * factor + 2;
            indicatorStyle = {
                top: this.props.indicatorPosition.min * factor,
                height: hgt,
                display: "block"
            }
        }

        return (<div className="strat-scale-frame" ref={this.contRef}>
            <div className="strat-scale-container">
                <div className="strat-scale-indicator" style={indicatorStyle}></div>
                <img onLoad={this.onImgLoad} src={base_url + "resources/images/echelle_brgm.png"} />
            </div>
        </div>)
    }
}


class GeologyMap extends React.Component {
    state = {
        infoPaneActivated: true,
        clickPosition: L.latLng(48.2, 0.3),
        center: [48.2, 0.3],
        geolPg: []
    }

    onClick = (e) => {
        // Affichage popup infos geol
        if (! this.props.geolInfoActivated) return;
        this.setState({clickPosition: e.latlng})
    }

    onSiteAdded = (e) => {
        const bounds = e.target.getBounds();
        this.setState({mapBounds: bounds});
    }

    onPopupDataFetched = (data) => {
        const ft = data.features[0];
        var coords = ft.geometry.coordinates.map(p => p.map(c => c.reverse()));
        this.setState({
            geolPg: coords,
            indicatorPosition: {
                min: ft.properties.pix_min,
                max: ft.properties.pix_max,
            }
        });
    }

    polygonStyle = () => {
        return {
            color: "green",
            width: 4,
            fill: false
        }
    }

    render() {
        let geolPopup, geolPg;
        if (this.props.geolInfoActivated) {
            geolPopup = <MapPopup position={this.state.clickPosition} onDataFetch={this.onPopupDataFetched} />;
            geolPg = <Polygon positions={this.state.geolPg} />;
        }

        return (<div id="map-container">
            <LeafletMap bounds={this.state.mapBounds} center={[48.2, 0.3]} zoom={12} onClick={this.onClick}>
                {geolPopup}
                <LayersControl position="topright">
                    <BaseLayer name="Orthophotos IGN">
                        <TileLayer url={ignOrthoUrl} attribution="IGN-F/Geoportail"
                            maxZoom="18" tileSize="256" />
                    </BaseLayer>
                    <BaseLayer name="Scan Express IGN">
                        <TileLayer url={ignScanUrl} attribution="IGN-F/Geoportail"
                            maxZoom="18" tileSize="256" />
                    </BaseLayer>
                    <BaseLayer name="Carte géologique harmonisée">
                        <WMSTileLayer url="http://geoservices.brgm.fr/geologie" attribution="&copy; BRGM"
                            layers="SCAN_H_GEOL50"
                            format="image/jpeg"
                            maxZoom="15" />
                    </BaseLayer>
                    <BaseLayer checked name="Carte géologique scannée">
                        <WMSTileLayer url="http://geoservices.brgm.fr/geologie" attribution="&copy; BRGM"
                            layers="GEOLOGIE"
                            format="image/jpeg"
                            maxZoom="15" />
                    </BaseLayer>
                </LayersControl>
                <GeoJSON data={this.props.siteGeom} style={this.polygonStyle} onAdd={this.onSiteAdded} />
                {geolPg}
            </LeafletMap>
            <BRGMScale indicatorPosition={this.state.indicatorPosition} />
        </div>)
    }
}

GeologyMap.defaultProps = {geolInfoActivated: true};