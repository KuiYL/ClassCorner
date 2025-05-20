<div id="modal" class="modal hidden">
    <div class="modal-content">
        <h2 id="modalTitle"></h2>
        <p id="modalText"></p>
        <form id="deleteForm" action="" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" id="classId" name="class_id" value="">
            <div class="modal-actions">
                <button type="button" id="cancelDelete" class="cancel-button">Отмена</button>
                <button type="submit" id="confirmDelete" class="confirm-button">Удалить</button>
            </div>
        </form>
    </div>
</div>
