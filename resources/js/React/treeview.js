var responses;


class TreeView extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            isLoaded: false
        };
    }

    componentDidMount() {
        $.get(site_url('api/get_responses_site/' + this.props.id), (data) => {
            responses = data;
            this.setState({isLoaded: true});
        });
    }

    render() {
        if (! this.state.isLoaded) {
            return <div>Chargement...</div>;
        } else {
            return (
                <TreeNode label={this.props.title} node_id={this.props.node_id} />
            );
        }
    }
}