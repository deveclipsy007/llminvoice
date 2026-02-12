/**
 * LLMInvoice - Kanban Board (Drag & Drop)
 */
(function () {
    'use strict';

    let draggedCard = null;
    let draggedFromColumn = null;
    let placeholder = null;

    // =========================================================================
    // Initialize Kanban
    // =========================================================================
    function initKanban() {
        const board = document.getElementById('kanban-board');
        if (!board) return;

        initDragAndDrop();
        initSearch();
        initFilters();
    }

    // =========================================================================
    // Drag & Drop
    // =========================================================================
    function initDragAndDrop() {
        // Create placeholder element
        placeholder = document.createElement('div');
        placeholder.className = 'h-20 rounded-2xl border-2 border-dashed border-lime/30 bg-lime/5 transition-all duration-200 mx-2 my-1';

        // Card drag events
        document.querySelectorAll('.card-kanban').forEach((card) => {
            card.setAttribute('draggable', 'true');

            card.addEventListener('dragstart', (e) => {
                draggedCard = card;
                draggedFromColumn = card.closest('[data-column-id]')?.dataset.columnId;
                card.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', card.dataset.clientId);

                // Ghost image
                const ghost = card.cloneNode(true);
                ghost.style.transform = 'rotate(3deg)';
                ghost.style.opacity = '0.8';
                document.body.appendChild(ghost);
                e.dataTransfer.setDragImage(ghost, 50, 30);
                setTimeout(() => ghost.remove(), 0);
            });

            card.addEventListener('dragend', () => {
                card.classList.remove('dragging');
                draggedCard = null;
                removePlaceholder();
                removeAllHighlights();
            });
        });

        // Column drop zones
        document.querySelectorAll('.kanban-column-cards').forEach((zone) => {
            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';

                const afterElement = getDragAfterElement(zone, e.clientY);
                if (afterElement) {
                    zone.insertBefore(placeholder, afterElement);
                } else {
                    zone.appendChild(placeholder);
                }
            });

            zone.addEventListener('dragenter', (e) => {
                e.preventDefault();
                const column = zone.closest('[data-column-id]');
                if (column) {
                    column.classList.add('ring-1', 'ring-lime/20');
                }
            });

            zone.addEventListener('dragleave', (e) => {
                // Only remove highlight if actually leaving the zone
                if (!zone.contains(e.relatedTarget)) {
                    const column = zone.closest('[data-column-id]');
                    if (column) {
                        column.classList.remove('ring-1', 'ring-lime/20');
                    }
                }
            });

            zone.addEventListener('drop', async (e) => {
                e.preventDefault();
                removePlaceholder();
                removeAllHighlights();

                if (!draggedCard) return;

                const targetColumn = zone.closest('[data-column-id]');
                const toColumnId = targetColumn?.dataset.columnId;
                const clientId = draggedCard.dataset.clientId;
                const afterElement = getDragAfterElement(zone, e.clientY);

                // Calculate new position
                const cards = [...zone.querySelectorAll('.card-kanban:not(.dragging)')];
                let newPosition = afterElement ? cards.indexOf(afterElement) : cards.length;

                if (toColumnId === draggedFromColumn) {
                    // Same column - reorder
                    await reorderCard(clientId, toColumnId, newPosition);
                } else {
                    // Different column - move
                    await moveCard(clientId, draggedFromColumn, toColumnId, newPosition);
                }
            });
        });
    }

    function getDragAfterElement(zone, y) {
        const cards = [...zone.querySelectorAll('.card-kanban:not(.dragging)')];
        return cards.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset, element: child };
            }
            return closest;
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function removePlaceholder() {
        if (placeholder.parentElement) {
            placeholder.remove();
        }
    }

    function removeAllHighlights() {
        document.querySelectorAll('[data-column-id]').forEach((col) => {
            col.classList.remove('ring-1', 'ring-lime/20');
        });
    }

    // =========================================================================
    // API Calls
    // =========================================================================
    async function moveCard(clientId, fromColumnId, toColumnId, newPosition) {
        try {
            const result = await api.post('/api/kanban/move', {
                client_id: clientId,
                from_column_id: fromColumnId,
                to_column_id: toColumnId,
                new_position: newPosition,
            });

            if (result.success) {
                // Move card in DOM
                const card = document.querySelector(`[data-client-id="${clientId}"]`);
                const targetZone = document.querySelector(`[data-column-id="${toColumnId}"] .kanban-column-cards`);
                if (card && targetZone) {
                    const cards = targetZone.querySelectorAll('.card-kanban');
                    if (cards[newPosition]) {
                        targetZone.insertBefore(card, cards[newPosition]);
                    } else {
                        targetZone.appendChild(card);
                    }
                    // Pulse animation
                    pulseColumn(toColumnId);
                    updateColumnCounts();
                }
                toast.success(result.message || 'Card moved successfully');
            }
        } catch (err) {
            if (err.errors) {
                showTransitionErrors(err.errors);
            } else {
                toast.error(err.error || 'Failed to move card');
            }
        }
    }

    async function reorderCard(clientId, columnId, newPosition) {
        try {
            await api.post('/api/kanban/reorder', {
                client_id: clientId,
                column_id: columnId,
                new_position: newPosition,
            });
        } catch (err) {
            toast.error(err.error || 'Failed to reorder');
        }
    }

    // =========================================================================
    // Visual Feedback
    // =========================================================================
    function pulseColumn(columnId) {
        const column = document.querySelector(`[data-column-id="${columnId}"]`);
        if (!column) return;

        column.classList.add('animate-pulse-lime');
        setTimeout(() => column.classList.remove('animate-pulse-lime'), 1000);
    }

    function updateColumnCounts() {
        document.querySelectorAll('[data-column-id]').forEach((col) => {
            const count = col.querySelectorAll('.card-kanban').length;
            const badge = col.querySelector('.column-count');
            if (badge) badge.textContent = count;
        });
    }

    function showTransitionErrors(errors) {
        const errorList = Array.isArray(errors) ? errors : [errors];
        const html = errorList.map((e) => `<li class="text-sm">${utils.escapeHtml(e)}</li>`).join('');

        const container = document.getElementById('modal-container');
        if (!container) return;

        container.innerHTML = `
            <div id="transition-error-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
                <div class="glass-panel p-6 max-w-md w-full mx-4 animate-slide-up border-l-4 border-danger">
                    <h3 class="text-danger font-semibold mb-3 flex items-center gap-2">
                        ${document.querySelector('[data-icon="alert-circle"]')?.innerHTML || '⚠️'}
                        Transition Not Allowed
                    </h3>
                    <ul class="space-y-1 text-text-body mb-4">${html}</ul>
                    <button onclick="modal.close('transition-error-modal'); document.getElementById('modal-container').innerHTML='';"
                            class="btn-secondary-sm">OK</button>
                </div>
            </div>
        `;
    }

    // =========================================================================
    // Search
    // =========================================================================
    function initSearch() {
        const searchInput = document.getElementById('kanban-search');
        if (!searchInput) return;

        const debouncedSearch = utils.debounce(async (query) => {
            if (query.length < 2) {
                // Show all cards
                document.querySelectorAll('.card-kanban').forEach((c) => {
                    c.style.display = '';
                    c.classList.remove('ring-1', 'ring-lime/30');
                });
                return;
            }

            try {
                const results = await api.get(`/api/kanban/search?q=${encodeURIComponent(query)}`);
                const matchIds = new Set(results.clients?.map((c) => String(c.id)) || []);

                document.querySelectorAll('.card-kanban').forEach((card) => {
                    if (matchIds.has(card.dataset.clientId)) {
                        card.style.display = '';
                        card.classList.add('ring-1', 'ring-lime/30');
                    } else {
                        card.style.display = 'none';
                        card.classList.remove('ring-1', 'ring-lime/30');
                    }
                });
            } catch (err) {
                console.error('Search failed:', err);
            }
        }, 300);

        searchInput.addEventListener('input', (e) => debouncedSearch(e.target.value));
    }

    // =========================================================================
    // Filters
    // =========================================================================
    function initFilters() {
        const temperatureFilter = document.getElementById('filter-temperature');
        const assignedFilter = document.getElementById('filter-assigned');

        [temperatureFilter, assignedFilter].forEach((filter) => {
            if (!filter) return;
            filter.addEventListener('change', applyFilters);
        });
    }

    async function applyFilters() {
        const temperature = document.getElementById('filter-temperature')?.value || '';
        const assigned = document.getElementById('filter-assigned')?.value || '';

        const params = new URLSearchParams();
        if (temperature) params.set('temperature', temperature);
        if (assigned) params.set('assigned_user_id', assigned);

        if (!params.toString()) {
            // Show all
            document.querySelectorAll('.card-kanban').forEach((c) => (c.style.display = ''));
            return;
        }

        try {
            const results = await api.get(`/api/kanban/filter?${params}`);
            const matchIds = new Set();
            (results.clients || []).forEach((c) => matchIds.add(String(c.id)));

            document.querySelectorAll('.card-kanban').forEach((card) => {
                card.style.display = matchIds.has(card.dataset.clientId) ? '' : 'none';
            });
        } catch (err) {
            console.error('Filter failed:', err);
        }
    }

    // =========================================================================
    // Init
    // =========================================================================
    document.addEventListener('DOMContentLoaded', initKanban);
})();
