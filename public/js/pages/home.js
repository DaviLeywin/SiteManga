export async function RenderPageHome(valor) {
    const GenerosDado = await FetchGender()
    const MangasDado = await FetchMangas()
    await CreateHtmlGender(GenerosDado);
    await CreateHtmlManga(MangasDado);
}
import { router } from '../router.js'

async function CreateHtmlGender(GenerosDado) {
    GenerosDado.forEach(genero =>{
        const mangas = document.getElementById('mangas');
        const MangasDoGenero = document.createElement('div');
        const NomeGenero = document.createElement('h2');
        const carouselWrapper = document.createElement('div');
        const btnEsq = document.createElement('button');
        const btnDir = document.createElement('button');
        const MangasGenero = document.createElement('div');

        MangasDoGenero.classList.add('genero');
        NomeGenero.classList.add('titulo-genero');
        carouselWrapper.classList.add('carousel-wrapper');
        MangasGenero.classList.add('carousel');
        btnEsq.classList.add('seta', 'esquerda');
        btnDir.classList.add('seta', 'direita');
        btnEsq.innerText = '◀';
        btnDir.innerText = '▶';
        
        MangasGenero.setAttribute('data-id', genero.ID);
        carouselWrapper.appendChild(btnEsq);
        carouselWrapper.appendChild(MangasGenero);
        carouselWrapper.appendChild(btnDir);
        MangasDoGenero.appendChild(NomeGenero);
        MangasDoGenero.appendChild(carouselWrapper);
        mangas.appendChild(MangasDoGenero);
        NomeGenero.innerText = genero.NOME;
        const passo = 250;
        btnDir.addEventListener('click', () => {
            MangasGenero.scrollLeft += passo;
        });
        btnEsq.addEventListener('click', () => {
            MangasGenero.scrollLeft -= passo;
        });
    })
}
async function CreateHtmlManga(MangasDado) {
    MangasDado.forEach(manga => {
        const ContainerManga = document.createElement('div');
        const CapaManga = document.createElement('img');
        const TituloManga = document.createElement('h3'); 
        ContainerManga.setAttribute('data-id-manga', manga.ID);
        ContainerManga.classList.add('manga');
        CapaManga.classList.add('capa-manga');
        TituloManga.classList.add('titulo-manga');
        if(manga.CAPA_URL !== null){
            CapaManga.src = '/SiteLivros/public/assets/'+manga.CAPA_URL;
            CapaManga.alt = '#';
        }
        else if(manga.CAPA_URL == null){
            CapaManga.src = "CAPA_URL";
            CapaManga.alt = '#';
        }
        TituloManga.innerText = manga.TITULO;
        const texto = manga.TITULO;
        const tamanho = texto.length;
        if(tamanho > 26){
            const valor = manga.TITULO.substr(0,23)+"...";
            TituloManga.innerText = valor;
        }
        ContainerManga.appendChild(CapaManga);
        ContainerManga.appendChild(TituloManga);
        manga.GENEROS.forEach(genero => {
            const containerGenero = document.querySelector(`.carousel[data-id="${genero.ID}"]`);
            if(!containerGenero)return;
            const clone = ContainerManga.cloneNode(true);
            containerGenero.appendChild(clone);
            clone.addEventListener('click', () => {
                const nomeManga = manga.TITULO.replaceAll(" ","-")
                history.pushState({id:manga.ID , nomeFormatado:nomeManga}, null, `/SiteLivros/Manga/${nomeManga}`);
                router.Execute();
            });
        }) 
    });
}
async function FetchGender() {
    const resposta = await fetch("/SiteLivros/api/GetAllGeneros",{
        method: 'GET',
    })
    const GenerosDados = await resposta.json();
    if(!GenerosDados.sucesso){return}
    return GenerosDados.resposta;
}
async function FetchMangas() {
    const resposta = await fetch("/SiteLivros/api/GetMangasGeneros",{
        method: 'GET',
    })
    const MangasDados = await resposta.json();
    if(!MangasDados.sucesso){return}
    return MangasDados.resposta;
}