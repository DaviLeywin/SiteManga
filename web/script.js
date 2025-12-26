const mangas = document.getElementById('mangas');

async function fetchMangas() {
    resposta = await fetch("../api/GetMangasGeneros",{
        method: 'GET',
    })
    const MangasDados = await resposta.json();
    if(!MangasDados.sucesso){
        alert("Erro ao carregar mangas");
        return;
    }
    else if(MangasDados.sucesso){
        MangasDados.resposta.forEach( manga => {
            const ContainerManga = document.createElement('div');
            const CapaManga = document.createElement('div');
            const TituloManga = document.createElement('h3');

            ContainerManga.setAttribute('data-id-manga', manga.ID);
            ContainerManga.addAtt

            ContainerManga.classList.add('manga');
            CapaManga.classList.add('capa-manga');
            TituloManga.classList.add('titulo-manga');

            manga.GENEROS.forEach(genero => {
                const containerGenero = document.querySelector(`.carousel[data-id="${genero.ID}"]`);
                if(!containerGenero)return;
                containerGenero.appendChild(ContainerManga)
            }) 
            TituloManga.innerText = manga.TITULO;
            ContainerManga.appendChild(CapaManga);
            ContainerManga.appendChild(TituloManga);
        });
    }
}
async function fetchGeneros() {
    resposta = await fetch("../api/GetAllGeneros",{
        method: 'GET',
    })
    const Generos = await resposta.json();
    Generos.resposta.forEach(genero =>{
        const MangasDoGenero = document.createElement('div');
        const NomeGenero = document.createElement('h2');
        const MangasGenero = document.createElement('div');
    
        MangasDoGenero.classList.add('genero');
        NomeGenero.classList.add('titulo-genero');
        MangasGenero.classList.add('carousel');

        mangas.appendChild(MangasDoGenero);
        MangasDoGenero.appendChild(NomeGenero);
        MangasDoGenero.appendChild(MangasGenero);

        MangasGenero.setAttribute('data-id',genero.ID);
        MangasGenero.setAttribute('data-nome',genero.NOME);


        NomeGenero.innerText = genero.NOME;
    })
}

fetchGeneros();
fetchMangas();