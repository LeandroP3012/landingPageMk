const menuToggle = document.getElementById('menuToggle');
const nav = document.querySelector('.nav');

menuToggle.addEventListener('click', () => {
  nav.classList.toggle('active');
});
window.addEventListener('load', () => {
  const heroContent = document.querySelector('.hero-content');
  heroContent.classList.add('show');
});
document.querySelectorAll('.clients-grid img').forEach((img, index) => {
  img.style.transitionDelay = `${index * 80}ms`;
});
const teamMembers = document.querySelectorAll('.team-member');

teamMembers.forEach((member, index) => {
  member.style.transitionDelay = `${index * 100}ms`;
});
