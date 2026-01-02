export async function RenderPageGender(params){
    const resposta = await FetchMangasByGender(params);
    await CreateHtmlGenders(resposta,params)
}
async function FetchGenderByName(params) {
    const resposta = await fetch(`/SiteLivros/api/GetGeneroPorNome/${params}`,{
        method:'GET'
    }) 
    const dados = await resposta.json();
    console.log(dados)
    if(!dados.sucesso)return;
    return dados.resposta[0];
}
async function FetchMangasByGender(params) {
    const genero = await FetchGenderByName(params);
    const id = genero.ID;
    const resposta = await fetch(`/SiteLivros/api/GetMangaPorId/${id}`,{
        method:'GET'
    })
    const dados = await resposta.json();
    if(!dados.sucesso)return;
    return dados.resposta;
}
import { router } from '../router.js'

async function CreateHtmlGenders(mangas, params) {
    mangas.forEach(manga => {
        const mangasHtml = document.getElementById('mangas');
        const generoHtml = document.getElementById('genero');
        generoHtml.innerText = params;
        
        const mangaHtml = document.createElement('div');
        const capaHtml = document.createElement('div');
        const imagenCapaHtml = document.createElement('img');
        const dadosDoMangaHtml = document.createElement('div');
        const nomeDoMangaHtml = document.createElement('span');
        const statusDoMangaHtml = document.createElement('span');
        
        mangaHtml.classList.add('manga');
        capaHtml.classList.add('capa');
        imagenCapaHtml.classList.add('imagem-da-capa');
        dadosDoMangaHtml.classList.add('dados-do-manga');
        nomeDoMangaHtml.classList.add('nome-do-manga');
        statusDoMangaHtml.classList.add('status-do-manga')

        mangasHtml.appendChild(mangaHtml);
        mangaHtml.appendChild(capaHtml);
        mangaHtml.appendChild(dadosDoMangaHtml);
        capaHtml.appendChild(imagenCapaHtml);
        dadosDoMangaHtml.appendChild(nomeDoMangaHtml);
        dadosDoMangaHtml.appendChild(statusDoMangaHtml);

        imagenCapaHtml.src = "/SiteLivros/public/assets/"+manga.CAPA_URL
        nomeDoMangaHtml.innerText = manga.TITULO
        statusDoMangaHtml.innerText = manga.STATUS

        mangaHtml.addEventListener('click', () => {
            const nomeManga = manga.TITULO.replaceAll(" ","-")
            history.pushState(null, null, `/SiteLivros/Manga/${nomeManga}`);
            router.Execute();
        });
        
    })
}