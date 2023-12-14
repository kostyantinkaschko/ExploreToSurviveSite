function addScrollListener() {
  const topLine = document.querySelector('.top-line');

  function handleScroll() {
    if (window.scrollY > 0) {
      topLine.classList.add('with-background');
    } else {
      topLine.classList.remove('with-background');
    }
  }

  window.addEventListener('scroll', handleScroll);

  return handleScroll;
}

// Перевірити розмір вікна при завантаженні та після зміни розміру
function checkWindowSize() {
  const handleScroll = addScrollListener();

  if (window.innerWidth <= 943) {
    // Розмір вікна менше або рівно 943 пікселів, тому видаляємо обробник подій
    window.removeEventListener('scroll', handleScroll);
  }
}

// Викликати функцію при завантаженні та при зміні розміру вікна
window.addEventListener('load', checkWindowSize);
window.addEventListener('resize', checkWindowSize);

// Функція для блокування прокрутки сторінки до 944 пікселів розміру монітора
function disableScroll() {
  // Отримуємо висоту монітора
  var monitorWeight = window.innerWeight;

  // Якщо висота монітора менше або дорівнює 944 пікселам, то блокуємо прокрутку
  if (monitorWeight <= 944) {
    // Заберігаємо поточну позицію прокрутки
    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

    // Забороняємо прокрутку сторінки
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';

    // Зафіксовуємо поточну позицію прокрутки
  }
}

document.getElementById("community-button").addEventListener("click", function() {
  var community = document.getElementById("community");
  community.classList.add("highlighted");
  
  setTimeout(function() {
    community.classList.remove("highlighted");
  }, 3000); // Видаляємо клас "highlighted" після 3 секунд
});
