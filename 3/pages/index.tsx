import { useState } from "react";
import { useRouter } from "next/router";
import { Button } from "../components/ui/button";
import { Textarea } from "../components/ui/textarea";
import { Input } from "../components/ui/input";
import { Code, ClipboardPaste } from "lucide-react";

export default function Home() {
  const [code, setCode] = useState("");
  const [language, setLanguage] = useState("javascript");
  const router = useRouter();

  const handleSubmit = async () => {
    const res = await fetch("/api/snippet", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ code, language }),
    });
    const data = await res.json();
    router.push(`/snip/${data.id}`);
  };

  return (
    <main className="max-w-xl mx-auto p-4">
      <h1 className="text-3xl font-bold mb-6 flex items-center gap-2">
        <Code className="text-pink-600" /> SnipShare
      </h1>
      <Textarea
        rows={10}
        value={code}
        onChange={(e: React.ChangeEvent<HTMLTextAreaElement>) =>
          setCode(e.target.value)
        }
        placeholder="Cole seu código aqui..."
        className="mb-4 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 w-full p-4"
        autoFocus
        spellCheck="false"
        autoCapitalize="none"
        autoCorrect="off"
        autoComplete="off"
      />
      <div className="flex items-center gap-4 mb-4">
        <select
          className="p-2 border rounded"
          value={language}
          onChange={(e: React.ChangeEvent<HTMLSelectElement>) =>
            setLanguage(e.target.value)
          }
        >
          <option value="javascript">JavaScript</option>
          <option value="python">Python</option>
          <option value="php">PHP</option>
          <option value="html">HTML</option>
        </select>
        <Button onClick={handleSubmit}>Salvar Snippet</Button>
      </div>
    </main>
  );
}
