$(() => {
    // Construction de l'arbre
    const mytree = <TreeView id={id_site} title={nom_site} level="Site" node_id={1017} />;
    ReactDOM.render(mytree, $("#main_tree").get(0));
});