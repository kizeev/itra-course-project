// assets/app.js
/*
 * Добро пожаловать в главный файл JavaScript вашего приложения!
 *
 * Мы рекомендуем включать встроенную версию этого файла JavaScript
 * (и его CSS-файл) в ваш базовый макет (base.html.twig).
 */

// любой CSS, который вы импортируете, будет выводиться в один css-файл (в этом случае app.css)
// import './styles/app.css';

// Нужен jQuery? Установите его с помощью "yarn и jquery", а затем раскомментируйте его для импорта.
// import $ from 'jquery';

const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;
};


const addFormDeleteLink = (attrFormLi) => {
    const removeFormButton = document.createElement('button')
    removeFormButton.classList
    removeFormButton.innerText = 'Delete this attribute'

    attrFormLi.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault()
        attrFormLi.remove();
    });
}

const attributes = document.querySelectorAll('li.attr_li')
attributes.forEach((tag) => {
    addFormDeleteLink(tag)
})
