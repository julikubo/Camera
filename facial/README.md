# Facial Recognition Login System - Vercel

Sistema de login com reconhecimento facial usando Supabase (TimeSupa) e deploy no Vercel.

## ✨ Características

- ✅ **100% Serverless** - Funciona no Vercel sem PHP
- ✅ **Reconhecimento Facial** - Usa face-api.js
- ✅ **Supabase** - Conecta ao banco do TimeSupa (read-only)
- ✅ **Sem Alterações** - Não modifica TimeSupa ou banco de dados
- ✅ **Session Token** - Autenticação via localStorage

## 📁 Estrutura

```
/proj/facial/
├── api/
│   ├── facial-login.js      # Login facial (Vercel Function)
│   ├── check-session.js     # Verificar sessão
│   └── logout.js            # Logout
├── models/                  # Modelos face-api.js
├── labeled_images/          # Fotos dos rostos
├── login.html              # Página de login
├── dashboard.html          # Dashboard
├── face-mapping.js         # Mapeamento rosto → email
├── faces.json              # Lista de imagens
├── package.json            # Dependências
├── vercel.json             # Config Vercel
└── .env                    # Credenciais Supabase
```

## 🚀 Deploy no Vercel

### 1. Instalar Dependências

```bash
cd /Applications/MAMP/htdocs/proj/facial
npm install
```

### 2. Configurar Mapeamento

Edite `face-mapping.js` e mapeie rostos para emails do TimeSupa:

```javascript
export const FACE_USER_MAPPING = {
    'Juliano Kubo': 'seu-email@timesupa.com',
};
```

### 3. Deploy

```bash
# Login no Vercel
vercel login

# Deploy
vercel --prod
```

## 🧪 Testar Localmente

```bash
# Instalar Vercel CLI
npm install -g vercel

# Rodar localmente
vercel dev
```

Acesse: `http://localhost:3000/login.html`

## 🔧 Configuração

### Supabase (TimeSupa)

Credenciais já configuradas em `vercel.json` e `.env`:

- **URL:** `https://nljeheupokqsvsuudlvt.supabase.co`
- **Anon Key:** Já configurada

### Face Mapping

Mapeie rostos reconhecidos para usuários do TimeSupa editando `face-mapping.js`.

## 📝 Como Funciona

1. **Login:** Usuário mostra rosto → face-api.js reconhece → API busca no Supabase → Cria session token
2. **Dashboard:** Verifica session token → Mostra dados do usuário
3. **Logout:** Remove session token

## ⚠️ Importante

- ✅ **NÃO altera** nada no TimeSupa
- ✅ **NÃO altera** banco de dados
- ✅ Apenas **lê** dados da tabela `users`
- ✅ Sessão gerenciada via localStorage (client-side)

## 🎯 Próximos Passos

1. Editar `face-mapping.js` com emails reais
2. `npm install`
3. `vercel --prod`
4. Acessar URL do Vercel
5. Testar login facial!
