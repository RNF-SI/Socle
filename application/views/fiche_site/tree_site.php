<div id="main_tree" class="container">
</div>
<script type="text/babel" src="<?= base_url('resources/js/React/treenode.js') ?>"></script>
<script type="text/babel" src="<?= base_url('resources/js/React/treeview.js') ?>"></script>
<script type="text/babel">
    const mytree = <TreeView id="<?= $id ?>" title="<?= $nom ?>" level="Site" node_id={1017} />;
    ReactDOM.render(mytree, $("#main_tree").get(0));
</script>