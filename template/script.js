// thanks to @HendrikSchoettle (https://github.com/HendrikSchoettle/AlbumPilot)

(function initAlbumTypeahead() {
    const input = document.getElementById('album-typeahead');
    const resultsBox = document.getElementById('album-typeahead-results');
    const select = document.getElementById('album-list');
    const hidden = document.getElementById('album-select');

    if (!input || !resultsBox || !select || !hidden)
        return;

    // Build complete folder path from indentation

    const albums = [];
    const stack = [];

    Array.from(select.options).forEach(opt => {
        const id = opt.value;
        const rawText = opt.textContent;
        const name = rawText.trim();

        // Determine depth of whitespace or &nbsp; (here two spaces = one level)
        const indent = (rawText.match(/^[\s\u00a0]+/) || [''])[0];
        const depth = Math.floor(indent.replace(/\u00a0/g, ' ').length / 3);

        // Update stack to current depth
        stack[depth] = name;
        stack.length = depth + 1;

        const fullPath = stack.join('/');

        albums.push({
            id,
            name,
            fullPath,
            displayPath: truncatePath(fullPath),
            lower: name.toLowerCase()
        });
    });

    // expose albums for external use (batch-mode, start-click)
    window.AlbumPilotAlbums = albums;

    // Helper: shorten paths that are too long with ‘...’
    function truncatePath(path, maxLen = 60) {
        return path.length <= maxLen ? path : '…' + path.slice(-maxLen);
    }

    input.addEventListener('input', function () {
        const query = input.value.trim().toLowerCase();
        resultsBox.innerHTML = '';
        if (!query) {
            resultsBox.style.display = 'none';
            return;
        }

        // Reset the current selection in the dropdown
        activeIndex = -1;

        const matches = albums
            .filter(album => {
                // if I enter a slash, search in the complete path
                if (query.includes('/')) {
                    return album.fullPath.toLowerCase().includes(query);
                }
                // otherwise as usual only in the folder name
                return album.name.toLowerCase().includes(query);
            })
            .slice(0, albums.length);

        if (matches.length === 0) {
            resultsBox.style.display = 'none';
            return;
        }

        matches.forEach(album => {
            const li = document.createElement('li');
            li.textContent = album.displayPath;
            li.title = album.fullPath; // Tooltip for full path


            li.dataset.id = album.id;
            li.style.cursor = 'pointer';
            li.style.padding = '4px 8px';

            const selectAlbum = () => {
                select.value = album.id;
                hidden.value = album.id;
                input.value = album.fullPath;
                resultsBox.style.display = 'none';

                generateExternalUrlFromSelection();

                const selectedOption = select.querySelector(`option[value="${album.id}"]`);
                if (selectedOption) {
                    selectedOption.selected = true;
                    select.scrollTop = selectedOption.offsetTop - select.clientHeight / 2;
                }
                select.dispatchEvent(new Event('change'));
            };

            // Mouse click
            li.addEventListener('mousedown', selectAlbum);

            // Keyboard: Enter (via click())
            li.addEventListener('click', selectAlbum);

            resultsBox.appendChild(li);
        });

        resultsBox.style.display = 'block';
    });

    // Keyboard navigation for dropdown
    let activeIndex = -1;

    input.addEventListener('keydown', function (e) {
        const items = Array.from(resultsBox.querySelectorAll('li'));
        if (!items.length)
            return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = activeIndex < items.length - 1 ? activeIndex + 1 : 0;
            updateActive(items, activeIndex);
        } else if (e.key === 'ArrowUp') {

            e.preventDefault();
            activeIndex = activeIndex > 0 ? activeIndex - 1 : items.length - 1;
            updateActive(items, activeIndex);
        } else if (e.key === 'Enter') {

            e.preventDefault();
            if (activeIndex >= 0) {
                items[activeIndex].click();
                activeIndex = -1;
            }
        }
    });

    function updateActive(items, index) {
        items.forEach((li, i) => {
            if (i === index) {
                li.style.backgroundColor = '#bde4ff';
                li.scrollIntoView({
                    block: 'nearest'
                });
            } else {
                li.style.backgroundColor = '';
            }
        });
    }

    // Select everything directly with focus in the text field
    input.addEventListener('focus', function () {
        input.select();
        resultsBox.style.display = 'none';
        resultsBox.innerHTML = '';
        activeIndex = -1;
    });

    input.addEventListener('blur', () => {
        setTimeout(() => resultsBox.style.display = 'none', 150);
    });

    // Selection in the <select> is reflected downwards in the text box
    select.addEventListener('change', () => {
        const opt = select.selectedOptions[0];
        if (!opt)
            return;
        // find the object for the selected ID in the albums array
        const found = albums.find(a => a.id === opt.value);
        if (found) {
            input.value = found.fullPath;
            // reset dropdown and index
            resultsBox.style.display = 'none';
            activeIndex = -1;
        }
    });

})();
