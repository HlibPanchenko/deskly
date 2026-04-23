import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import client from '../api/client';

export default function DashboardPage() {
    const { user, logout } = useAuth();
    const navigate = useNavigate();
    const [boards, setBoards] = useState([]);
    const [loading, setLoading] = useState(true);
    const [newBoardName, setNewBoardName] = useState('');
    const [creating, setCreating] = useState(false);

    useEffect(() => {
        fetchBoards();
    }, []);

    async function fetchBoards() {
        try {
            const response = await client.get('/boards');
            setBoards(response.data);
        } finally {
            setLoading(false);
        }
    }

    async function createBoard(e) {
        e.preventDefault();
        if (!newBoardName.trim()) return;
        setCreating(true);
        try {
            const response = await client.post('/boards', { name: newBoardName });
            setBoards([response.data, ...boards]);
            setNewBoardName('');
        } finally {
            setCreating(false);
        }
    }

    async function handleLogout() {
        await logout();
        navigate('/login');
    }

    return (
        <div className="min-h-screen bg-gray-50">
            <header className="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h1 className="text-xl font-bold text-gray-900">TaskFlow</h1>
                <div className="flex items-center gap-4">
                    <span className="text-sm text-gray-600">{user?.name}</span>
                    <button
                        onClick={handleLogout}
                        className="text-sm text-gray-500 hover:text-gray-900 transition"
                    >
                        Выйти
                    </button>
                </div>
            </header>

            <main className="max-w-5xl mx-auto px-6 py-8">
                <h2 className="text-2xl font-bold text-gray-900 mb-6">Мои доски</h2>

                <form onSubmit={createBoard} className="flex gap-3 mb-8">
                    <input
                        type="text"
                        value={newBoardName}
                        onChange={(e) => setNewBoardName(e.target.value)}
                        placeholder="Название новой доски"
                        className="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <button
                        type="submit"
                        disabled={creating}
                        className="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg transition disabled:opacity-50"
                    >
                        {creating ? 'Создаём...' : '+ Создать'}
                    </button>
                </form>

                {loading ? (
                    <p className="text-gray-500">Загрузка...</p>
                ) : boards.length === 0 ? (
                    <p className="text-gray-500">Досок пока нет. Создайте первую!</p>
                ) : (
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        {boards.map((board) => (
                            <div
                                key={board.id}
                                onClick={() => navigate(`/boards/${board.id}`)}
                                className="bg-white border border-gray-200 rounded-xl p-5 cursor-pointer hover:shadow-md hover:border-blue-300 transition"
                            >
                                <h3 className="font-semibold text-gray-900">{board.name}</h3>
                                {board.description && (
                                    <p className="text-sm text-gray-500 mt-1">{board.description}</p>
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </main>
        </div>
    );
}
