import {
  getPublicKey,
  getEventHash,
  nip19,
  nip04
} from "nostr-tools";
import { finalizeEvent } from "nostr-tools/pure";
import { SimplePool } from 'nostr-tools/pool'

const pool = new SimplePool();
const relays = [
  "wss://relayable.org",
  "wss://relay.damus.io", 
  "wss://nostr-pub.wellorder.net",
  "wss://relay.nostr.band"
];
let activeRelays = [];
let relayUrl;
let userPrivKeyHex, userPubKeyHex, receiverPubKeyHex;

async function connectToRelays() {

  activeRelays = [];

  const connectionPromises = relays.map(async (url) => {
    try {
      const relay = await pool.ensureRelay(url);
      console.log(`✅ Conectado ao relay: ${url}`);
      logMessage(`Conectado ao relay: ${url}`);
      activeRelays.push(url);
      return url;
    } catch (error) {
      console.warn(`⚠️ Erro ao conectar ao relay ${url}:`, error);
      return null;
    }
  });

  const results = await Promise.allSettled(connectionPromises);
  
  const firstConnected = results.find(r => r.status === 'fulfilled' && r.value)?.value;
  if (firstConnected) {
    relayUrl = firstConnected;
    return true;
  } else {
    logMessage("❌ Não foi possível conectar a nenhum relay");
    return false;
  }
}

document.getElementById("privKey").addEventListener("input", async (e) => {
  const nsecOrHex = e.target.value.trim();
  userPrivKeyHex = nsecOrHex.startsWith("nsec")
    ? nip19.decode(nsecOrHex).data
    : nsecOrHex;
  userPubKeyHex = getPublicKey(userPrivKeyHex);
  console.log("🔐 PubKey:", userPubKeyHex);

  subscribeToDMs(userPubKeyHex);
});

document.getElementById("receiverPubKey").addEventListener("input", (e) => {
  const npubOrHex = e.target.value.trim();
  receiverPubKeyHex = npubOrHex.startsWith("npub")
    ? nip19.decode(npubOrHex).data
    : npubOrHex;
  console.log("🔑 Receiver PubKey:", receiverPubKeyHex);
});

async function sendMessage() {
  const content = document.getElementById("message").value.trim();
  if (!content || !userPrivKeyHex || !receiverPubKeyHex) return;

  const encrypted = await nip04.encrypt(
    userPrivKeyHex,
    receiverPubKeyHex,
    content
  );
  const eventTemplate = {
    kind: 4,
    created_at: Math.floor(Date.now() / 1000),
    tags: [["p", receiverPubKeyHex]],
    content: encrypted,
    pubkey: userPubKeyHex,
  };

  const signedEvent = finalizeEvent(eventTemplate, userPrivKeyHex);

  await pool.publish(activeRelays, signedEvent);

  logMessage("Você: " + content);
}

function subscribeToDMs(pubkey) {
  
  const sub = pool.subscribeMany(
    activeRelays, 
    [
      {
        kinds: [4],
        limit: 100,
        authors: [pubkey],
      },
      {
        kinds: [4],
        limit: 100,
        "#p": [pubkey],
      },
      {
        kinds: [4],
        limit: 100,
        authors: [receiverPubKeyHex],
      },
      {
        kinds: [4],
        limit: 100,
        "#p": [receiverPubKeyHex],
      },
    ],
    {
      onevent: async (event) => {
        const isSender = event.pubkey === pubkey;
        console.log("📨 Evento recebido:", event);
        const isRecipient = event.tags.some(
          (tag) => tag[0] === "p" && tag[1] === pubkey
        );

        if (!isSender && !isRecipient) return;

        const otherPubKey = isSender
          ? event.tags.find((tag) => tag[0] === "p")[1]
          : event.pubkey;

        try {
          const msg = await nip04.decrypt(
            userPrivKeyHex,
            otherPubKey,
            event.content
          );
          logMessage(
            (isSender ? "Enviado " : "Recebido: ") +
              " " +
              otherPubKey.slice(0, 12) +
              ": " +
              msg
          );
        } catch (e) {
          console.warn("⚠️ Falha ao descriptografar:", e);
        }
      },
    }
  );
}

function logMessage(text) {
  const messagesDiv = document.getElementById("messages");
  messagesDiv.innerHTML += `<div>${text}</div>`;
  messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

const messageInput = document.getElementById("message");

window.addEventListener("DOMContentLoaded", async () => {
  const sendButton = document.getElementById("sendMessage");
  sendButton.addEventListener("click", sendMessage);
  messageInput.value = "";
  messageInput.focus();
  
  await connectToRelays();
});

messageInput.addEventListener("keydown", (event) => {
  if (event.key === "Enter") {
    event.preventDefault();
    sendMessage();
    messageInput.value = "";
    messageInput.focus();
  }
});
