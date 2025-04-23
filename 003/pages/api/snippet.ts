import { NextApiRequest, NextApiResponse } from "next";

let db: Record<string, { code: string; language: string; views: number }> = {};

export default function handler(req: NextApiRequest, res: NextApiResponse) {
  if (req.method === "POST") {
    const { code, language } = req.body;

    if (!code) {
      return res.status(400).json({ error: "Código é obrigatório" });
    }

    const id = Math.random().toString(36).substring(2, 8);
    db[id] = { code, language: language || "javascript", views: 0 };

    console.log(`Snippet criado: ${id}`);
    return res.status(200).json({ id });
  } else if (req.method === "GET") {
    const { id } = req.query;

    if (typeof id !== "string") {
      return res.status(400).json({ error: "ID inválido" });
    }

    console.log(`Buscando snippet: ${id}, Existe: ${Boolean(db[id])}`);

    if (!db[id]) {
      return res.status(404).json({ error: "Snippet não encontrado" });
    }

    db[id].views++;
    return res.status(200).json(db[id]);
  } else {
    return res.status(405).json({ error: "Método não permitido" });
  }
}
