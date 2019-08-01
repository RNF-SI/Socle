$(() => {
    // Construction de l'arbre
    const mytree = <TreeView id={id_site} title={nom_site} level="Site" node_id={1017} />;
    ReactDOM.render(mytree, $("#main_tree").get(0));

    // ajout de la carte
    $.get(site_url("carto/site_geom/" + id_site), data => {
        const theMap = <GeologyMap site_id={id_site} siteGeom={data.features} />;
        ReactDOM.render(theMap, $("#map").get(0));
    });

});