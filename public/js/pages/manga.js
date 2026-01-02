export async function RenderPageManga(titulo) {
    const MangasDado = await FetchMangas(titulo)
    await CreateHtmlManga(MangasDado);
}

import { router } from '../router.js'

async function FetchMangas(titulo) {
    console.log(titulo)
    const resposta = await fetch(`/SiteLivros/api/GetMangaGeneroAutorCapitulos/${titulo}`,{
        method: 'GET',
    })
    const MangasDados = await resposta.json();
    if(!MangasDados.sucesso){return}
    return MangasDados.resposta;
}

async function CreateHtmlManga(manga) {
    const capa = document.getElementById('capa-do-manga');
    capa.src = '/SiteLivros/public/assets/'+manga.CAPA_URL
    
    const titulo = document.getElementById('titulo');
    titulo.innerHTML = manga.TITULO;
    
    const autor = document.getElementById('autor');
    autor.innerHTML = manga.AUTOR.NOME;
    
    const tipo = document.getElementById('tipo');
    tipo.innerHTML = manga.TIPO;
    
    const status = document.getElementById('status');
    status.innerHTML = manga.STATUS;
    
    const sinopse = document.getElementById('sinopse');
    sinopse.innerHTML = manga.SINOPSE;
    
    const criadoquando = document.getElementById('criado-quando');
    criadoquando.innerHTML = manga.CRIADO_QUANDO;
    
    const generos = document.getElementById('generos');
    const linksGeneros = [];
    
    manga.GENEROS.forEach(genero =>{
        const linkGenero = document.createElement('a');
        linkGenero.classList.add('genero-de-generos');
        
        const generoNome = genero.NOME
        const id = genero.ID
        
        linkGenero.innerText = generoNome;
        linkGenero.setAttribute('data-id',id);
        linkGenero.setAttribute('data-genero',generoNome);
        linksGeneros.push(linkGenero);
        linkGenero.addEventListener('click',(e)=>{
            e.preventDefault;
            const generofinal = generoNome.replaceAll(" ","-")
            history.pushState(null, null, `/SiteLivros/Genero/${generofinal}`);
            router.Execute()
        })   
    })
    generos.innerHTML = ''; 
    
    linksGeneros.forEach((link, index) => {
        if (index > 0) {generos.append(', ');}
        generos.appendChild(link);
    });
    const capitulos = document.getElementById('container-capitulos');
    manga.CAPITULOS.forEach(capitulo => {

        const containercapitulo = document.createElement('div');
        containercapitulo.classList.add('container-capitulo');
        containercapitulo.setAttribute('id',capitulo.ID)

        const numerocapitulocontainer = document.createElement('div');
        numerocapitulocontainer.classList.add('numero-capitulo-container')
        
        const textocapitulo = document.createElement('span');
        const numerocapitulo = document.createElement('span');

        numerocapitulocontainer.appendChild(textocapitulo);
        numerocapitulocontainer.appendChild(numerocapitulo);

        textocapitulo.classList.add('texto-capitulo');
        textocapitulo.innerText = "Capitulo:";
        
        numerocapitulo.classList.add('numero-capitulo');
        numerocapitulo.innerText = capitulo.NUMERO_CAPITULO;
        
        const titulocapitulo = document.createElement('span');
        titulocapitulo.innerText = capitulo.TITULO_CAPITULO;

        containercapitulo.appendChild(numerocapitulocontainer);
        containercapitulo.appendChild(titulocapitulo);
         
        capitulos.appendChild(containercapitulo)
        containercapitulo.addEventListener('click',() =>{
            const nomeManga = state.nomeFormatado
            history.pushState({mangaID:manga.ID},null,`/SiteLivros/Manga/${nomeManga}/Capitulo/${capitulo.NUMERO_CAPITULO}`)
        })
    })
}