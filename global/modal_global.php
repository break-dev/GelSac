<div class="modal fade" id="modal_global" tabindex="-1" aria-labelledby="modal_globalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div id="modal_global_content" class="modal-content">
      <div class="modal-header" style="background-color: #f8da62;">
        <h1 class="modal-title fs-5">Lista de </h1>
        <h1 class="modal-title fs-5" id="modalglobal_Titulo" style="margin-left: 5px;"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="background-color: #737373; padding: 0px; height: 80vh;">
        <div class="d-flex" style="padding: 5px;">
          <label style="color: #ffffff; margin-top: 8px;">
            Buscar:
          </label>

          <input id="modalglobal_find" type="text" class="form-control" style="font-size: 14px; margin-left: 5px; text-transform: uppercase;" onkeyup="f_ModalGlobal_Finding();">

          <img src="<?php echo $img_find ?>" style="margin-left: 5px; width: 40px;">
        </div>

        <div class="d-flex">
          <table class="table table-dark table-striped" style="width: 100%; margin-bottom: 0px;">
            <tbody id="modalglobal_TablaOpciones">

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>