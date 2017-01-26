import Questions from './components/questions';

window.onload = () => {
    if($('.question').length) {
        Questions.init();
    }
}
