import { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import client from '../api/client';

export default function BoardPage() {
    const { id } = useParams();
    const navigate = useNavigate();
    const [board, setBoard] = useState(null);
    const [loading, setLoading] = useState(true);
    const [newColumnName, setNewColumnName] = useState('');

    useEffect(() => {
        fetchBoard();
    }, [id]);

    async function fetchBoard() {
        try {
            const response = await client.get(`/boards/${id}`);
            setBoard(response.data);
        } catch {
            navigate('/');
        } finally {
            setLoading(false);
        }
    }

    async function addColumn(e) {
        e.preventDefault();
        if (!newColumnName.trim()) return;
        const response = await client.post(`/boards/${id}/columns`, { name: newColumnName });
        setBoard({ ...board, columns: [...board.columns, { ...response.data, cards: [] }] });
        setNewColumnName('');
    }

    async function addCard(columnId, title) {
        const response = await client.post(`/columns/${columnId}/cards`, { title });
        setBoard({
            ...board,
            columns: board.columns.map((col) =>
                col.id === columnId
                    ? { ...col, cards: [...col.cards, response.data] }
                    : col
            ),
        });
    }

    async function deleteCard(columnId, cardId) {
        await client.delete(`/cards/${cardId}`);
        setBoard({
            ...board,
            columns: board.columns.map((col) =>
                col.id === columnId
                    ? { ...col, cards: col.cards.filter((c) => c.id !== cardId) }
                    : col
            ),
        });
    }

    if (loading) return <div className="min-h-screen flex items-center justify-center">Загрузка...</div>;

    return (
        <div className="min-h-screen bg-blue-700">
            <header className="px-6 py-4 flex items-center gap-4">
                <button
                    onClick={() => navigate('/')}
                    className="text-white/80 hover:text-white text-sm transition"
                >
                    ← Назад
                </button>
                <h1 className="text-white font-bold text-xl">{board.name}</h1>
            </header>

            <div className="px-6 pb-6 flex gap-4 overflow-x-auto">
                {board.columns.map((column) => (
                    <Column
                        key={column.id}
                        column={column}
                        onAddCard={addCard}
                        onDeleteCard={deleteCard}
                    />
                ))}

                <div className="w-72 shrink-0">
                    <form onSubmit={addColumn} className="bg-white/20 rounded-xl p-3">
                        <input
                            type="text"
                            value={newColumnName}
                            onChange={(e) => setNewColumnName(e.target.value)}
                            placeholder="Название колонки"
                            className="w-full bg-white rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none"
                        />
                        <button
                            type="submit"
                            className="w-full bg-white/30 hover:bg-white/40 text-white text-sm font-medium py-1.5 rounded-lg transition"
                        >
                            + Добавить колонку
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
}

function Column({ column, onAddCard, onDeleteCard }) {
    const [newCardTitle, setNewCardTitle] = useState('');
    const [adding, setAdding] = useState(false);

    async function handleAddCard(e) {
        e.preventDefault();
        if (!newCardTitle.trim()) return;
        await onAddCard(column.id, newCardTitle);
        setNewCardTitle('');
        setAdding(false);
    }

    return (
        <div className="w-72 shrink-0 bg-gray-100 rounded-xl p-3 flex flex-col gap-2">
            <h3 className="font-semibold text-gray-800 px-1">{column.name}</h3>

            {column.cards.map((card) => (
                <div key={card.id} className="bg-white rounded-lg p-3 shadow-sm group flex justify-between items-start">
                    <span className="text-sm text-gray-800">{card.title}</span>
                    <button
                        onClick={() => onDeleteCard(column.id, card.id)}
                        className="text-gray-300 hover:text-red-400 text-xs opacity-0 group-hover:opacity-100 transition ml-2"
                    >
                        ✕
                    </button>
                </div>
            ))}

            {adding ? (
                <form onSubmit={handleAddCard} className="mt-1">
                    <input
                        type="text"
                        value={newCardTitle}
                        onChange={(e) => setNewCardTitle(e.target.value)}
                        placeholder="Название задачи"
                        autoFocus
                        className="w-full border border-blue-400 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none"
                    />
                    <div className="flex gap-2">
                        <button type="submit" className="bg-blue-600 text-white text-sm px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                            Добавить
                        </button>
                        <button type="button" onClick={() => setAdding(false)} className="text-gray-500 text-sm px-2 hover:text-gray-700">
                            Отмена
                        </button>
                    </div>
                </form>
            ) : (
                <button
                    onClick={() => setAdding(true)}
                    className="text-gray-500 hover:text-gray-800 text-sm text-left px-1 py-1 hover:bg-gray-200 rounded transition"
                >
                    + Добавить задачу
                </button>
            )}
        </div>
    );
}
