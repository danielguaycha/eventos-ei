<!-- Modal -->
<div class="modal fade" id="deleteConfirm" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="delete-form" method="post">
            @csrf
            @method('delete')
            <div class="modal-header">
                <input type="hidden" id="delete-id">
                <h5 class="modal-title" id="staticBackdropLabel">Confirmar eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (isset($msg))
                    {{ $msg }}
                @else
                    ¿Esta seguro que desea eliminar este recurso?
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Confirmar</button>
            </div>
        </form>
    </div>
</div>
