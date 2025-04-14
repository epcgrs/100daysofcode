import { useRouter } from "next/router";
import { useEffect, useState } from "react";
import { Prism as SyntaxHighlighter } from "react-syntax-highlighter";
import { oneDark } from "react-syntax-highlighter/dist/cjs/styles/prism";
import { Clipboard } from "lucide-react";

export default function SnippetView() {
  const router = useRouter();
  const { id } = router.query;
  const [snippet, setSnippet] = useState<any>(null);
  const [copied, setCopied] = useState(false);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    if (!id) return;

    setLoading(true);
    fetch(`/api/snippet?id=${id}`)
      .then(async (res) => {
        if (!res.ok) {
          const errorData = await res
            .json()
            .catch(() => ({ error: "Erro desconhecido" }));
          throw new Error(errorData.error || `Erro ${res.status}`);
        }
        return res.json();
      })
      .then((data) => {
        setSnippet(data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Erro ao carregar snippet:", err);
        setError(err.message || "Não foi possível carregar o snippet");
        setLoading(false);
      });
  }, [id]);

  const handleCopy = () => {
    navigator.clipboard.writeText(snippet.code);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  if (loading) return <p className="p-4">Carregando...</p>;

  if (error) {
    return (
      <main className="max-w-3xl mx-auto p-4">
        <div className="bg-red-50 border border-red-200 text-red-700 p-4 rounded">
          <h2 className="font-bold">Erro</h2>
          <p>{error}</p>
          <button
            onClick={() => router.push("/")}
            className="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            Voltar ao início
          </button>
        </div>
      </main>
    );
  }

  if (!snippet) return null;

  return (
    <main className="max-w-3xl mx-auto p-4">
      <div className="flex items-center justify-between mb-4">
        <h1 className="text-xl font-bold">Snippet: {id}</h1>
        <button
          onClick={handleCopy}
          className="flex items-center gap-1 border px-3 py-1 rounded hover:bg-gray-100"
        >
          <Clipboard size={16} />
          {copied ? "Copiado!" : "Copiar"}
        </button>
      </div>
      <SyntaxHighlighter language={snippet.language} style={oneDark}>
        {snippet.code}
      </SyntaxHighlighter>
    </main>
  );
}
