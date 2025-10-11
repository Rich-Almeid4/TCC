// tema e tamanho de fonte
;(() => {

  // colocar o tema
  function applyTheme(theme) {
    const body = document.body
    console.log(" Aplicando tema:", theme)

    if (theme === "dark") {
      body.classList.add("dark-theme")
    } else if (theme === "light") {
      body.classList.remove("dark-theme")
    } else if (theme === "auto") {

      // preferência do sistema
      const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches
      if (prefersDark) {
        body.classList.add("dark-theme")
      } else {
        body.classList.remove("dark-theme")
      }
    }
  }

  // tamanho da fonte

  function applyFontSize(size) {
    const html = document.documentElement
    console.log(" Aplicando tamanho de fonte:", size)

    // tira as classes de tamanho de fonte
    html.classList.remove("font-small", "font-medium", "font-large", "font-extra-large")

    // coloca a classe correspondente ao tamanho selecionado
    if (size === "small") {
      html.classList.add("font-small")
    } else if (size === "medium") {
      html.classList.add("font-medium")
    } else if (size === "large") {
      html.classList.add("font-large")
    } else if (size === "extra-large") {
      html.classList.add("font-extra-large")
    }

    console.log(" Classes no HTML:", html.className)
  }

  //carregar

  // carregar o tema salvo
  function loadTheme() {
    const savedTheme = localStorage.getItem("arthropoda-theme") || "light"
    console.log(" Carregando tema salvo:", savedTheme)
    applyTheme(savedTheme)

    // atualiza o select se existir na página
    const themeSelect = document.getElementById("theme")
    if (themeSelect) {
      themeSelect.value = savedTheme
    }
  }

  function loadFontSize() {
    const savedFontSize = localStorage.getItem("arthropoda-font-size") || "medium"
    console.log(" Carregando tamanho de fonte salvo:", savedFontSize)
    applyFontSize(savedFontSize)

    // atualiza se existir na página
    const fontSizeSelect = document.getElementById("font-size")
    if (fontSizeSelect) {
      fontSizeSelect.value = savedFontSize
    }
  }

  // salvar o tema
  function saveTheme(theme) {
    console.log(" Salvando tema:", theme)
    localStorage.setItem("arthropoda-theme", theme)
    applyTheme(theme)
  }

  function saveFontSize(size) {
    console.log(" Salvando tamanho de fonte:", size)
    localStorage.setItem("arthropoda-font-size", size)
    applyFontSize(size)
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      console.log(" DOM carregado, aplicando configurações")
      loadTheme()
      loadFontSize()
    })
  } else {
    console.log(" DOM já carregado, aplicando configurações")
    loadTheme()
    loadFontSize()
  }

  window.ThemeManager = {
    apply: applyTheme,
    save: saveTheme,
    load: loadTheme,
  }

  window.FontSizeManager = {
    apply: applyFontSize,
    save: saveFontSize,
    load: loadFontSize,
  }

  //  modo automático
  window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (e) => {
    const currentTheme = localStorage.getItem("arthropoda-theme")
    if (currentTheme === "auto") {
      applyTheme("auto")
    }
  })
})()
