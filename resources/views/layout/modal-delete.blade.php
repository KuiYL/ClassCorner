<div id="modal" class="modal fixed inset-0 z-1031 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div
        class="modal-content w-full max-w-md p-6 bg-white rounded-lg shadow-lg opacity-0 transform scale-95 transition-transform duration-300 ease-out">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 id="modalTitle" class="text-xl font-semibold text-gray-800">Удалить элемент</h2>
            <button type="button" id="cancelDeleteHeaderBtn" aria-label="Close"
                class="btn-close text-gray-500 hover:text-gray-700">
            </button>
        </div>

        <p id="modalText" class="text-gray-700 mb-6 text-center">
            Вы уверены, что хотите удалить этот элемент? Это действие необратимо.
        </p>

        <form id="deleteForm" action="" method="POST" class="text-center">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id" id="modalItemId">

            <div class="modal-actions flex justify-center gap-3">
                <button type="button" id="cancelDeleteFormBtn" aria-label="Close"
                    class="btn btn-secondary px-4 py-2 rounded-md bg-gray-300 hover:bg-gray-400 text-gray-800 transition duration-200">
                    <i class="fas fa-times mr-1"></i> Отмена
                </button>
                <input type="hidden" name="return_url" value="{{ url()->current() }}">
                <button type="submit"
                    class="btn btn-danger px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white transition duration-200">
                    <i class="fas fa-trash mr-1"></i> Удалить
                </button>
            </div>
        </form>
    </div>
</div>
