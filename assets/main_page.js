import './styles/main_page.css';
import { createElement as h, useEffect, useMemo, useState } from 'react';
import { createRoot } from 'react-dom/client';
import htm from 'htm';

const html = htm.bind(h);

function StatusBadge({ isBorrowed }) {
    const modifier = isBorrowed ? 'status-badge--borrowed' : 'status-badge--available';

    return html`<span class=${`status-badge ${modifier}`}>${isBorrowed ? 'Wypożyczona' : 'Dostępna'}</span>`;
}

function BookCard({ book }) {
    return html`
        <li class="book-card">
            <div class="book-card__header">
                <h3 class="book-card__title">${book.name}</h3>
                <${StatusBadge} isBorrowed=${book.isBorrowed} />
            </div>
            <p class="book-card__author">${book.author}</p>
            <dl class="book-card__meta">
                <div>
                    <dt>Nr ewidencyjny</dt>
                    <dd>${book.serialNumber}</dd>
                </div>
                ${book.borrower ? html`
                    <div>
                        <dt>Wypożyczający</dt>
                        <dd>${book.borrower.name} ${book.borrower.surname}</dd>
                    </div>
                ` : null}
                ${book.borrowedAt ? html`
                    <div>
                        <dt>Data wypożyczenia</dt>
                        <dd>${new Date(book.borrowedAt).toLocaleDateString('pl-PL')}</dd>
                    </div>
                ` : null}
            </dl>
        </li>
    `;
}

function BookLibraryApp() {
    const [books, setBooks] = useState(null);
    const [error, setError] = useState(null);
    const [query, setQuery] = useState('');

    useEffect(() => {
        let cancelled = false;

        fetch('/api/books', { headers: { Accept: 'application/json' } })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Serwer zwrócił błąd ${response.status}`);
                }

                return response.json();
            })
            .then((data) => {
                if (!cancelled) {
                    setBooks(data);
                }
            })
            .catch((fetchError) => {
                if (!cancelled) {
                    setError(fetchError.message);
                }
            });

        return () => {
            cancelled = true;
        };
    }, []);

    const filteredBooks = useMemo(() => {
        if (!books) {
            return [];
        }

        const needle = query.trim().toLowerCase();

        if (!needle) {
            return books;
        }

        return books.filter(
            (book) => book.name.toLowerCase().includes(needle) || book.author.toLowerCase().includes(needle),
        );
    }, [books, query]);

    const availableCount = useMemo(() => (books ?? []).filter((book) => !book.isBorrowed).length, [books]);

    if (error) {
        return html`<div class="state-banner state-banner--error">Nie udało się pobrać listy książek: ${error}</div>`;
    }

    if (!books) {
        return html`<div class="state-banner">Wczytywanie listy książek…</div>`;
    }

    return html`
        <div>
            <header class="library-header">
                <div>
                    <h1>Biblioteka</h1>
                    <p class="library-header__subtitle">
                        ${books.length} ${books.length === 1 ? 'książka' : 'książek'} w katalogu · ${availableCount} dostępnych
                    </p>
                </div>
                <input
                    class="library-search"
                    type="search"
                    placeholder="Szukaj po tytule lub autorze…"
                    value=${query}
                    onInput=${(event) => setQuery(event.target.value)}
                />
            </header>
            ${filteredBooks.length === 0
                ? html`<div class="state-banner">Brak książek spełniających kryteria wyszukiwania.</div>`
                : html`<ul class="book-grid">
                    ${filteredBooks.map((book) => html`<${BookCard} key=${book.serialNumber} book=${book} />`)}
                </ul>`}
        </div>
    `;
}

const container = document.getElementById('book-library-root');

if (container) {
    createRoot(container).render(html`<${BookLibraryApp} />`);
}
