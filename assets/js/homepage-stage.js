document.addEventListener("DOMContentLoaded", () => {
  const categoryLinks = Array.from(
    document.querySelectorAll(".homepage-stage__category-link")
  );
  const background = document.getElementById("homepage-background");
  const backgroundLink = document.getElementById("homepage-background-link");

  if (!categoryLinks.length || !background || !backgroundLink) {
    return;
  }

  const CATEGORY_INTERVAL = 5000;

  let categoryState = categoryLinks.map((link) => ({
    link,
    projects: parseProjects(link).filter((project) => project && project.image),
    currentProjectIndex: 0,
  }));

  let activeCategoryIndex = Math.max(
    0,
    categoryLinks.findIndex((link) => link.classList.contains("is-active"))
  );

  let autoCycleInterval = null;

  function parseProjects(link) {
    const rawProjects = link.dataset.projects;

    if (!rawProjects) {
      return [];
    }

    try {
      const parsed = JSON.parse(rawProjects);
      return Array.isArray(parsed) ? parsed : [];
    } catch (error) {
      console.error("Failed to parse category projects:", error);
      return [];
    }
  }

  function setActiveCategoryClass(activeLink) {
    categoryLinks.forEach((link) => {
      link.classList.toggle("is-active", link === activeLink);
    });
  }

  function updateBackground(project) {
    if (!project || !project.image) {
      return;
    }

    background.style.backgroundImage = `url("${project.image}")`;
    backgroundLink.href = project.link || "#";
    backgroundLink.setAttribute("aria-label", project.title || "Open project");
  }

  function showCurrentProjectForCategory(categoryIndex) {
    const category = categoryState[categoryIndex];

    if (!category || !category.projects.length) {
      return;
    }

    const project = category.projects[category.currentProjectIndex];
    updateBackground(project);
    setActiveCategoryClass(category.link);
  }

  function activateCategory(categoryIndex, advanceImage = false) {
    const category = categoryState[categoryIndex];

    if (!category || !category.projects.length) {
      return;
    }

    activeCategoryIndex = categoryIndex;

    if (advanceImage && category.projects.length > 1) {
      category.currentProjectIndex =
        (category.currentProjectIndex + 1) % category.projects.length;
    }

    showCurrentProjectForCategory(categoryIndex);
  }

  function moveToNextAvailableCategory() {
    if (!categoryState.length) {
      return;
    }

    let attempts = 0;
    let nextIndex = activeCategoryIndex;

    do {
      nextIndex = (nextIndex + 1) % categoryState.length;
      attempts += 1;
    } while (
      attempts <= categoryState.length &&
      (!categoryState[nextIndex] || !categoryState[nextIndex].projects.length)
    );

    if (categoryState[nextIndex] && categoryState[nextIndex].projects.length) {
      activateCategory(nextIndex, true);
    }
  }

  function stopAutoCycle() {
    if (autoCycleInterval !== null) {
      clearInterval(autoCycleInterval);
      autoCycleInterval = null;
    }
  }

  function startAutoCycle() {
    stopAutoCycle();

    autoCycleInterval = setInterval(() => {
      moveToNextAvailableCategory();
    }, CATEGORY_INTERVAL);
  }

  categoryLinks.forEach((link, index) => {
    link.addEventListener("mouseenter", () => {
      activateCategory(index, false);
      startAutoCycle();
    });

    link.addEventListener("focus", () => {
      activateCategory(index, false);
      startAutoCycle();
    });
  });

  if (!categoryState[activeCategoryIndex] || !categoryState[activeCategoryIndex].projects.length) {
    activeCategoryIndex = categoryState.findIndex(
      (category) => category.projects.length
    );

    if (activeCategoryIndex === -1) {
      return;
    }
  }

  activateCategory(activeCategoryIndex, false);
  startAutoCycle();
});