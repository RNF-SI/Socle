// Composant affichant une carte Leaflet de base

const {Map: LeafletMap, TileLayer, WMSTileLayer,
    LayersControl, Marker, Popup, DivOverlay,
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
    getGeolInfo = (map, position, callback) => {
        const url = site_url('carto/featureInfoProxy');
        const size = map.getSize();
        const xy = map.latLngToLayerPoint(position);
        const params = {
            BBOX: map.getBounds().toBBoxString(),
            WIDTH: size.x,
            HEIGHT: size.y,
            X: Math.round(xy.x),
            Y: Math.round(xy.y)
        };
        $.get(url, params, callback);
    }

    updateLeafletElement(fromProps, toProps) {
        if ("position" in toProps && ! toProps.position.equals(fromProps.position)) {
            const map = toProps.leaflet.map;
            if (map.getZoom() < 11) return false;
            var popup = this.leafletElement;
            popup.setContent("Chargement...").setLatLng(toProps.position).openOn(map);
            this.getGeolInfo(map, toProps.position, function(data) {
                var cont = '<p><b>Entité géologique :</b><br />' + data.notation + ' : <i>'
                    + data.description + '</i></p><p><a href="http://ficheinfoterre.brgm.fr/Notices/'
                    + ("0000" + data.carte).slice(-4)
                    + 'N.pdf" target="_blank">consulter la notice</a></p>';
                popup.setContent(cont);
            });
        }
    }
}

const MapPopup = withLeaflet(_MapPopup);


class GeologyMap extends React.Component {
    state = {
        infoPaneActivated: true,
        clickPosition: [48.2, 0.3],
        center: [48.2, 0.3]
    }

    onClick = (e) => {
        // Affichage popup infos geol
        if (! this.props.geolInfoActivated) return;
        this.setState({clickPosition: e.latlng})
    }

    render() {
        let geolPopup;
        if (this.props.geolInfoActivated) {
            geolPopup = <MapPopup position={this.state.clickPosition} />
        }

        return (<div id="map-container">
            <LeafletMap center={this.state.center} zoom={12} onClick={this.onClick}>
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
                    <BaseLayer checked name="Carte géologique">
                    <WMSTileLayer url="http://geoservices.brgm.fr/geologie" attribution="&copy; BRGM"
                        layers="GEOLOGIE"
                        format="image/jpeg"
                        maxZoom="15" />
                    </BaseLayer>
                </LayersControl>
            </LeafletMap>
        </div>)
    }
}

GeologyMap.defaultProps = {geolInfoActivated: true};