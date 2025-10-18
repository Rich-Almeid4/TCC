// Sistema de gerenciamento de tema e tamanho de fonte
;(() => {
  // Função para aplicar o tema
  function applyTheme(theme) {
    const body = document.body

    if (theme === "dark") {
      body.classList.add("dark-theme")
    } else if (theme === "light") {
      body.classList.remove("dark-theme")
    } else if (theme === "auto") {
      // Detecta preferência do sistema
      const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches
      if (prefersDark) {
        body.classList.add("dark-theme")
      } else {
        body.classList.remove("dark-theme")
      }
    }
  }

  function applyFontSize(size) {
    const html = document.documentElement

    // Remove todas as classes de tamanho de fonte
    html.classList.remove("font-small", "font-medium", "font-large", "font-extra-large")

    // Adiciona a classe correspondente ao tamanho selecionado
    if (size === "small") {
      html.classList.add("font-small")
    } else if (size === "medium") {
      html.classList.add("font-medium")
    } else if (size === "large") {
      html.classList.add("font-large")
    } else if (size === "extra-large") {
      html.classList.add("font-extra-large")
    }
  }

  // Função para carregar o tema salvo
  function loadTheme() {
    const savedTheme = localStorage.getItem("arthropoda-theme") || "light"
    applyTheme(savedTheme)

    // Atualiza o select se existir na página
    const themeSelect = document.getElementById("theme")
    if (themeSelect) {
      themeSelect.value = savedTheme
    }
  }

  function loadFontSize() {
    const savedFontSize = localStorage.getItem("arthropoda-font-size") || "medium"
    applyFontSize(savedFontSize)

    // Atualiza o select se existir na página
    const fontSizeSelect = document.getElementById("font-size")
    if (fontSizeSelect) {
      fontSizeSelect.value = savedFontSize
    }
  }

  // Função para salvar o tema
  function saveTheme(theme) {
    localStorage.setItem("arthropoda-theme", theme)
    applyTheme(theme)
  }

  function saveFontSize(size) {
    localStorage.setItem("arthropoda-font-size", size)
    applyFontSize(size)
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      loadTheme()
      loadFontSize()
    })
  } else {
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

  // Listener para mudanças na preferência do sistema (modo automático)
  window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (e) => {
    const currentTheme = localStorage.getItem("arthropoda-theme")
    if (currentTheme === "auto") {
      applyTheme("auto")
    }
  })
})()

// Sistema de espaçamento de linha
;(() => {
  function applyLineSpacing(spacing) {
    console.log("[v0] Aplicando espaçamento de linha:", spacing)
    const body = document.body

    body.classList.remove("line-spacing-normal", "line-spacing-relaxed", "line-spacing-loose")

    if (spacing === "normal") {
      body.classList.add("line-spacing-normal")
    } else if (spacing === "relaxed") {
      body.classList.add("line-spacing-relaxed")
    } else if (spacing === "loose") {
      body.classList.add("line-spacing-loose")
    }

    console.log("[v0] Classes do body após aplicar espaçamento:", body.className)
  }

  function loadLineSpacing() {
    const savedSpacing = localStorage.getItem("arthropoda-line-spacing") || "normal"
    console.log("[v0] Carregando espaçamento de linha salvo:", savedSpacing)
    applyLineSpacing(savedSpacing)

    const lineSpacingSelect = document.getElementById("line-spacing")
    if (lineSpacingSelect) {
      lineSpacingSelect.value = savedSpacing
    }
  }

  function saveLineSpacing(spacing) {
    console.log("[v0] Salvando espaçamento de linha:", spacing)
    localStorage.setItem("arthropoda-line-spacing", spacing)
    applyLineSpacing(spacing)
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", loadLineSpacing)
  } else {
    loadLineSpacing()
  }

  window.LineSpacingManager = {
    apply: applyLineSpacing,
    save: saveLineSpacing,
    load: loadLineSpacing,
  }
})()

// Sistema de alto contraste
;(() => {
  function applyHighContrast(enabled) {
    console.log("[v0] Aplicando alto contraste:", enabled)
    const body = document.body

    if (enabled) {
      body.classList.add("high-contrast")
    } else {
      body.classList.remove("high-contrast")
    }

    console.log("[v0] Classes do body após aplicar alto contraste:", body.className)
  }

  function loadHighContrast() {
    const savedHighContrast = localStorage.getItem("arthropoda-high-contrast") === "true"
    console.log("[v0] Carregando alto contraste salvo:", savedHighContrast)
    applyHighContrast(savedHighContrast)

    const highContrastCheckbox = document.getElementById("high-contrast")
    if (highContrastCheckbox) {
      highContrastCheckbox.checked = savedHighContrast
    }
  }

  function saveHighContrast(enabled) {
    console.log("[v0] Salvando alto contraste:", enabled)
    localStorage.setItem("arthropoda-high-contrast", enabled.toString())
    applyHighContrast(enabled)
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", loadHighContrast)
  } else {
    loadHighContrast()
  }

  window.HighContrastManager = {
    apply: applyHighContrast,
    save: saveHighContrast,
    load: loadHighContrast,
  }
})()

// Sistema de modo para daltonismo
;(() => {
  function applyColorBlindMode(mode) {
    console.log("[v0] Aplicando modo de daltonismo:", mode)
    const body = document.body

    body.classList.remove("protanopia", "deuteranopia", "tritanopia")

    if (mode === "protanopia") {
      body.classList.add("protanopia")
    } else if (mode === "deuteranopia") {
      body.classList.add("deuteranopia")
    } else if (mode === "tritanopia") {
      body.classList.add("tritanopia")
    }

    console.log("[v0] Classes do body após aplicar daltonismo:", body.className)
  }

  function loadColorBlindMode() {
    const savedMode = localStorage.getItem("arthropoda-color-blind-mode") || "none"
    console.log("[v0] Carregando modo de daltonismo salvo:", savedMode)
    applyColorBlindMode(savedMode)

    const colorBlindSelect = document.getElementById("color-blind-mode")
    if (colorBlindSelect) {
      colorBlindSelect.value = savedMode
    }
  }

  function saveColorBlindMode(mode) {
    console.log("[v0] Salvando modo de daltonismo:", mode)
    localStorage.setItem("arthropoda-color-blind-mode", mode)
    applyColorBlindMode(mode)
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", loadColorBlindMode)
  } else {
    loadColorBlindMode()
  }

  window.ColorBlindManager = {
    apply: applyColorBlindMode,
    save: saveColorBlindMode,
    load: loadColorBlindMode,
  }
})()

// Sistema de perfil
;(() => {
  // Função para atualizar o perfil na visualização
  function updateProfile() {
    const inputName = document.getElementById("inputName")
    const username = document.getElementById("username")
    if (inputName && username && inputName.value) {
      username.innerText = inputName.value
    }

    const email = document.getElementById("email")
    const idgmail = document.getElementById("idgmail")
    if (email && idgmail && email.value) {
      idgmail.innerText = email.value
    }
  }

  // Função para preview do avatar
  function setupAvatarPreview() {
    const avatarUpload = document.getElementById("avatar-upload")
    if (avatarUpload) {
      avatarUpload.addEventListener("change", (event) => {
        const file = event.target.files[0]
        if (file) {
          const reader = new FileReader()
          reader.onload = (e) => {
            const fotoPerfil = document.getElementById("foto-perfil")
            if (fotoPerfil) {
              fotoPerfil.src = e.target.result
            }
          }
          reader.readAsDataURL(file)
        }
      })
    }
  }

  // Função para alternar entre abas de configurações
  function switchTab(tabName) {
    const tabs = document.querySelectorAll(".tab-btn")
    const contents = document.querySelectorAll(".settings-content")

    tabs.forEach((tab) => tab.classList.remove("active"))
    contents.forEach((content) => content.classList.remove("active"))

    const activeTab = Array.from(tabs).find((tab) =>
      tab.textContent.toLowerCase().includes(tabName === "gerais" ? "gerais" : "acessibilidade"),
    )
    if (activeTab) {
      activeTab.classList.add("active")
    }

    const activeContent = document.getElementById(tabName)
    if (activeContent) {
      activeContent.classList.add("active")
    }
  }

  // Função para salvar configurações
  function saveSettings() {
    const themeSelect = document.getElementById("theme")
    if (themeSelect) {
      const selectedTheme = themeSelect.value
      window.ThemeManager.save(selectedTheme)
    }

    const fontSizeSelect = document.getElementById("font-size")
    if (fontSizeSelect) {
      const selectedFontSize = fontSizeSelect.value
      window.FontSizeManager.save(selectedFontSize)
    }

    const lineSpacingSelect = document.getElementById("line-spacing")
    if (lineSpacingSelect) {
      const selectedLineSpacing = lineSpacingSelect.value
      window.LineSpacingManager.save(selectedLineSpacing)
    }

    const highContrastCheckbox = document.getElementById("high-contrast")
    if (highContrastCheckbox) {
      window.HighContrastManager.save(highContrastCheckbox.checked)
    }

    const colorBlindSelect = document.getElementById("color-blind-mode")
    if (colorBlindSelect) {
      const selectedColorBlindMode = colorBlindSelect.value
      window.ColorBlindManager.save(selectedColorBlindMode)
    }

    showNotification("Configurações salvas com sucesso!")
  }

  // Função para mostrar notificação
  function showNotification(message) {
    const existingNotification = document.querySelector(".theme-notification")
    if (existingNotification) {
      existingNotification.remove()
    }

    const notification = document.createElement("div")
    notification.className = "theme-notification"
    notification.textContent = message
    document.body.appendChild(notification)

    setTimeout(() => {
      notification.style.animation = "slideOut 0.3s ease"
      setTimeout(() => notification.remove(), 300)
    }, 3000)
  }

  // Inicialização quando o DOM estiver pronto
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      setupAvatarPreview()
    })
  } else {
    setupAvatarPreview()
  }

  // Exportar funções para uso global
  window.ProfileManager = {
    updateProfile,
    setupAvatarPreview,
  }

  window.SettingsManager = {
    switchTab,
    saveSettings,
    showNotification,
  }
})()
