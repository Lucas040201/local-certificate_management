const disableButton = (root, findClass = 'see_more') => {
    root.find(`.${findClass}`).addClass('hidden');
    root.find(`.${findClass}`).attr('disabled', true);
}

const activeButton = (root, findClass = 'see_more') => {
    root.find(`.${findClass}`).removeClass('hidden');
    root.find(`.${findClass}`).attr('disabled', false);
}

export default {
    activeButton,
    disableButton
}