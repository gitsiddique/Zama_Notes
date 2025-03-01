<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ZAMA Notes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* (Include your custom CSS here) */
        .note-card {
            margin: 20px auto;
            width: 800px;
            min-height: 150px;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
        }

        .note-card.editing {
            min-height: 400px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .note-content {
            overflow: hidden;
            position: relative;
            word-wrap: break-word;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .color-palette {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .color-option {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
        }

        .color-option.selected {
            border-color: #000;
            transform: scale(1.1);
        }

        .color-option:hover {
            transform: scale(1.1);
        }

        .note-textarea {
            width: 100%;
            border: none;
            resize: none;
            background: transparent;
            font-family: inherit;
            font-size: 1.2rem;
            line-height: 1.6;
            padding: 10px;
        }

        .note-textarea:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .modal-dialog {
            max-width: 800px;
        }

        .modal-content {
            min-height: 300px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1" style="font-size: 2rem;">ZAMA Notes</span>
            <button class="btn btn-outline-success btn-lg" data-bs-toggle="modal" data-bs-target="#noteModal">Take a
                note</button>
        </div>
    </nav>

    <div class="container mt-4">
        <div id="notesContainer"></div>
    </div>

    <!-- Note Modal -->
    <div class="modal fade" id="noteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="text" class="form-control border-0 fs-5" placeholder="Title" id="noteTitle" />
                </div>
                <div class="modal-body">
                    <textarea class="form-control border-0 fs-5" placeholder="Take a note..." id="noteContent" rows="6"
                        style="min-height: 200px"></textarea>
                    <div class="color-palette mt-3">
                        <div class="color-option selected" style="background-color: #ffffff" data-color="#ffffff"></div>
                        <div class="color-option" style="background-color: #f28b82" data-color="#f28b82"></div>
                        <div class="color-option" style="background-color: #fbbc04" data-color="#fbbc04"></div>
                        <div class="color-option" style="background-color: #fff475" data-color="#fff475"></div>
                        <div class="color-option" style="background-color: #ccff90" data-color="#ccff90"></div>
                        <div class="color-option" style="background-color: #a7ffeb" data-color="#a7ffeb"></div>
                        <div class="color-option" style="background-color: #cbf0f8" data-color="#cbf0f8"></div>
                        <div class="color-option" style="background-color: #aecbfa" data-color="#aecbfa"></div>
                        <div class="color-option" style="background-color: #d7aefb" data-color="#d7aefb"></div>
                        <div class="color-option" style="background-color: #fdcfe8" data-color="#fdcfe8"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button class="btn btn-primary btn-lg" onclick="saveNewNote()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set CSRF token header for all fetch requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let notes = [];

        // Fetch and render notes on page load
        document.addEventListener('DOMContentLoaded', fetchNotes);

        // Initialize color selection in the modal
        document.querySelectorAll('#noteModal .color-option').forEach(option => {
            option.addEventListener('click', (e) => {
                document.querySelectorAll('#noteModal .color-option').forEach(o => o.classList.remove('selected'));
                e.target.classList.add('selected');
            });
        });

        function fetchNotes() {
            fetch('/notes')
                .then(response => response.json())
                .then(data => {
                    notes = data;
                    renderNotes();
                });
        }

        function createNoteElement(note, editMode = false) {
            const element = document.createElement('div');
            element.className = `note-card${editMode ? ' editing' : ''}`;
            element.style.backgroundColor = note.color || '#fff';
            element.dataset.id = note.id;

            if (editMode) {
                element.innerHTML = `
          <input type="text" class="note-textarea mb-3 fs-4" value="${note.title ?? ''}" placeholder="Title">
          <textarea class="note-textarea" rows="12" placeholder="Note content">${note.content}</textarea>
          <div class="color-palette mt-3">
            <div class="color-option" style="background-color: #ffffff" data-color="#ffffff"></div>
            <div class="color-option" style="background-color: #f28b82" data-color="#f28b82"></div>
            <div class="color-option" style="background-color: #fbbc04" data-color="#fbbc04"></div>
            <div class="color-option" style="background-color: #fff475" data-color="#fff475"></div>
            <div class="color-option" style="background-color: #ccff90" data-color="#ccff90"></div>
            <div class="color-option" style="background-color: #a7ffeb" data-color="#a7ffeb"></div>
            <div class="color-option" style="background-color: #cbf0f8" data-color="#cbf0f8"></div>
            <div class="color-option" style="background-color: #aecbfa" data-color="#aecbfa"></div>
            <div class="color-option" style="background-color: #d7aefb" data-color="#d7aefb"></div>
            <div class="color-option" style="background-color: #fdcfe8" data-color="#fdcfe8"></div>
          </div>
          <div class="mt-4 text-end">
            <button class="btn btn-lg btn-danger" onclick="deleteNote(${note.id})">Delete</button>
            <button class="btn btn-lg btn-primary" onclick="saveNote(this)">Save</button>
          </div>
        `;

                // Mark the current color as selected
                const colorOptions = element.querySelectorAll('.color-option');
                colorOptions.forEach(option => {
                    if (option.dataset.color === note.color) {
                        option.classList.add('selected');
                    }
                    option.addEventListener('click', function () {
                        colorOptions.forEach(o => o.classList.remove('selected'));
                        this.classList.add('selected');
                        element.style.backgroundColor = this.dataset.color;
                    });
                });
            } else {
                const truncated = note.content.length > 800;
                const content = truncated ? note.content.substring(0, 800) + '...' : note.content;

                element.innerHTML = `
          ${note.title ? `<h4>${note.title}</h4>` : ''}
          <div class="note-content">
            <p class="mb-0">${content}</p>
          </div>
        `;
                element.addEventListener('click', () => enterEditMode(element, note));
            }
            return element;
        }

        function renderNotes() {
            const container = document.getElementById('notesContainer');
            container.innerHTML = '';

            notes.forEach(note => {
                const element = createNoteElement(note);
                container.appendChild(element);
            });
        }

        function enterEditMode(element, note) {
            const newElement = createNoteElement(note, true);
            element.parentNode.replaceChild(newElement, element);
            newElement.querySelector('textarea').focus();
        }

        // Create a new note via AJAX
        function saveNewNote() {
            const title = document.getElementById('noteTitle').value;
            const content = document.getElementById('noteContent').value;
            const color = document.querySelector('#noteModal .color-option.selected').dataset.color;

            if (content) {
                fetch('/notes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ title, content, color })
                })
                    .then(response => response.json())
                    .then(note => {
                        // Add the new note at the beginning of the notes array and re-render
                        notes.unshift(note);
                        renderNotes();
                        bootstrap.Modal.getInstance(document.getElementById('noteModal')).hide();
                        document.getElementById('noteTitle').value = '';
                        document.getElementById('noteContent').value = '';
                    });
            }
        }

        // Update an existing note via AJAX
        function saveNote(button) {
            const noteElement = button.closest('.note-card');
            const id = noteElement.dataset.id;
            const title = noteElement.querySelector('input')?.value || '';
            const content = noteElement.querySelector('textarea').value;
            const color = noteElement.querySelector('.color-option.selected').dataset.color;

            if (content) {
                fetch(`/notes/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ title, content, color })
                })
                    .then(response => response.json())
                    .then(updatedNote => {
                        // Update the note in the array and re-render
                        const index = notes.findIndex(note => note.id == id);
                        if (index > -1) {
                            notes[index] = updatedNote;
                        }
                        renderNotes();
                    });
            }
        }

        // Delete a note via AJAX
        function deleteNote(id) {
            fetch(`/notes/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(response => response.json())
                .then(() => {
                    notes = notes.filter(note => note.id != id);
                    renderNotes();
                });
        }
    </script>
</body>

</html>