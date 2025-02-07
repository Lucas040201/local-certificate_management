import Templates from "core/templates";
import Utils from 'local_certificate_management/utils';
import Repository from 'local_certificate_management/repository';

const getParams = async root => {
    return {
        courseId: Number(root.attr('data-courseid')),
        limit: Number(root.attr('data-limit')),
        page: Number(root.attr('data-page')),
        search: root.find('#search-param').val() || '',
        sort: root.find('#order-param').val() || '',
    };
}

const usersComponents = {
    notFound: 'local_certificate_management/components/users/table-row-not-found',
    empty: 'local_certificate_management/components/users/table-row-empty',
    content: 'local_certificate_management/components/users/table-row'
}

const loadUsers = async (root, seeMore = false) => {
    try {
        const params = await getParams(root);
        const data = await Repository.retrieveUsers(params);

        let templateToLoad = usersComponents.content;

        if (!seeMore && !data.users.length && !params.search) {
            templateToLoad = usersComponents.empty;
        }

        if (!seeMore && !data.users.length && params.search) {
            Utils.disableButton(root);
            templateToLoad = usersComponents.notFound;
        }

        if (!seeMore) {
            root.find('.users-content').empty();
            root.find('.total-users').text(0);
        }

        const html = await Templates.render(templateToLoad, {users: data.users});

        root.find('.total-users').text(data.total)

        root.find('.users-content').append(html);

        const coursesLength = root.find('.users-item').length;

        if (data.total === coursesLength) {
            Utils.disableButton(root);
        } else if (root.find('.see_more').hasClass('hidden') && data.total > coursesLength) {
            Utils.activeButton(root);
        }
    } catch (error) {
        console.log(error);
        root.find('.users-content').empty();
        root.find('.total-users').text(0);
        const html = await Templates.render(usersComponents.empty, {});
        Utils.disableButton(root);
        root.find('.users-content').append(html);
    }

}

const init = async root => {
    await loadUsers(root);

    let timeout;
    root.find('#search-param').on('keyup', e => {

        clearTimeout(timeout);

        timeout = setTimeout(async () => {
            await loadUsers(root);
        }, 2000);
    });

    root.find('#order-param').on('change', async event => {
        await loadUsers(root);
    });

    root.find('.see_more').on('click', async function () {
        const newPage = Number(root.attr('data-page')) + 1;
        root.attr('data-page', newPage);
        await loadUsers(root, true);
    });
}

export default {
    init
};