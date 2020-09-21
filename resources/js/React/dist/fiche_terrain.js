$(function () {
    // Construction de l'arbre
    var mytree = React.createElement(TreeView, { id: id_site, title: "Arborescence des caract\xE9ristiques", level: "Site", node_id: 1017 });
    ReactDOM.render(mytree, $("#main_tree").get(0));

    // ajout de la carte
    $.get(site_url("carto/site_geom/" + id_site), function (data) {
        var theMap = React.createElement(GeologyMap, { site_id: id_site, siteGeom: data.features });
        ReactDOM.render(theMap, $("#map-component").get(0));
    });
});