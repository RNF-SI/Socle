<div id="login-form-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Identification</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="login-message"></div>
        <form id="login-form">
          <div class="form-group">
            <label for="email">Adresse mail :</label>
            <input type="email" class="form-control" id="email" name="email" />
          </div>
          <div class="form-group">
            <label for="pwd">Mot de passe :</label>
            <input type="password" class="form-control" id="password" name="password" />
          </div>
          <button type="submit" class="btn btn-primary">OK</button>
        </form>
        <p><?= anchor('utilisateurs/forgot_password', 'Mot de passe oublié ?') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
      </div>
    </div>

  </div>
</div>
