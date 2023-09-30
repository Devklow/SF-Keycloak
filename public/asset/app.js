let tooltipelements = document.querySelectorAll("[data-bs-toggle='tooltip']");
tooltipelements.forEach((el) => {
    new bootstrap.Tooltip(el);
});