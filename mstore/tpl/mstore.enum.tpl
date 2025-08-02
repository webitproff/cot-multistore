<!-- BEGIN: MAIN -->

    <!-- BEGIN: MSTORE_ROW -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title fs-6 mb-3">
                <a href="{MSTORE_ROW_URL}" title="{MSTORE_ROW_TITLE}">{MSTORE_ROW_TITLE}</a>
            </h3>

            <!-- MSTORE description (if exists) -->
            <!-- IF {MSTORE_ROW_DESCRIPTION} -->
            <p class="card-text small text-muted">{MSTORE_ROW_DESCRIPTION}</p>
            <!-- ENDIF -->

            <!-- MSTORE text preview -->
            <div class="card-text">
                {LIST_ROW_TEXT_CUT}
            </div>
        </div>
    </div>
    <!-- END: MSTORE_ROW -->

    <!-- Pagination (if exists) -->
    <!-- IF {PAGINATION} -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            {PREVIOUS_PAGE}
            {PAGINATION}
            {NEXT_PAGE}
        </ul>
    </nav>
    <!-- ENDIF -->

<!-- END: MAIN -->