import $ from 'jquery';
import Templates from "core/templates";
import ModalDefault from 'core/modal';
import ModalEvents from 'core/modal_events';
import {get_string as getString} from 'core/str';
import ModalSaveCancel from 'core/modal_save_cancel';
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
        await issueCertificate(root);
        await issueHistory(root);
    } catch (error) {
        root.find('.users-content').empty();
        root.find('.total-users').text(0);
        const html = await Templates.render(usersComponents.empty, {});
        Utils.disableButton(root);
        root.find('.users-content').append(html);
    }

}

const issueHistory = async root => {
    root.find('.btn-history').off();
    root.find('.btn-history').on('click', async clickEvent => {

        const hasHistory = $(clickEvent.currentTarget).attr('data-hashistory');
        if(hasHistory === 'true') {
            regenHistory(root, clickEvent);
            return;
        }
        const userId = $(clickEvent.currentTarget).attr('data-userid');
        const userFullName = $(clickEvent.currentTarget).attr('data-username');

        const modal = await ModalDefault.create({
            title: await getString('modal_issue_history_title', 'local_certificate_management'),
            body: await getString('generate_history', 'local_certificate_management', userFullName),
            isVerticallyCentered: true,
        });

        modal.setFooter(await Templates.render('local_certificate_management/components/modal/modal_footer_gen_history', {}));
        modal.getRoot().find('.btn.issue').on('click', async event => {
            const params = await getParams(root);

            await issueHistoryCall(params, userId, userFullName, modal);
        });

        await modal.show();
    });
}

const regenHistory = async (root, event) => {
    const userId = $(event.currentTarget).attr('data-userid');
    const userFullName = $(event.currentTarget).attr('data-username');
    const modal = await ModalDefault.create({
        title: await getString('modal_regen_history', 'local_certificate_management'),
        body: await getString('regen_history', 'local_certificate_management'),
        isVerticallyCentered: true,
    });

    modal.setFooter(await Templates.render('local_certificate_management/components/modal/modal_footer_regen_history', {}));

    modal.getRoot().find('.btn.regen').on('click', async event => {
        const params = await getParams(root);


        await issueHistoryCall(params, userId, userFullName, modal);
    });

    modal.getRoot().find('.btn.see').on('click', async event => {
        const params = await getParams(root);

        Repository.getHistoryUrl({
            courseId: Number(params.courseId),
            userId: Number(userId),
        }).then(async (response) => {
            window.open(response.history, "_blank");
        }).catch(async () => {
            modal.hide();
            await ModalDefault.create({
                title: await getString('modal_certified_issued_with_error_title', 'local_certificate_management'),
                body: await getString('modal_certified_issued_not_found_body', 'local_certificate_management'),
                show: true,
                isVerticallyCentered: true
            });
        });
    });

    await modal.show();
}

const issueHistoryCall = async (params, userId, userFullName, modal) => {
    Repository.issueHistory({
        courseId: Number(params.courseId),
        userId: Number(userId),
    }).then(async (response) => {
        modal.destroy();
        await ModalDefault.create({
            title: await getString('modal_history_issued_with_success_title', 'local_certificate_management'),
            body: await getString('modal_history_issued_with_success_body', 'local_certificate_management', {
                history: response.history,
                name: userFullName
            }),
            show: true,
            isVerticallyCentered: true
        });
    }).catch(async () => {
        modal.destroy();
        await ModalDefault.create({
            title: await getString('modal_history_issued_with_error_title', 'local_certificate_management'),
            body: await getString('modal_history_issued_with_error_body', 'local_certificate_management', userFullName),
            show: true,
            isVerticallyCentered: true
        });
    });
}

const issueCertificate = async root => {
    root.find('.btn-certificate').off();
    root.find('.btn-certificate').on('click', async clickEvent => {

        const hasCertificate = $(clickEvent.currentTarget).attr('data-hascertificate');
        if(hasCertificate === 'true') {
            regenCertificate(root, clickEvent);
            return;
        }
        const response = await Repository.retrieveTemplates();
        const userId = $(clickEvent.currentTarget).attr('data-userid');
        const userFullName = $(clickEvent.currentTarget).attr('data-username');

        const modal = await ModalDefault.create({
            title: await getString('modal_issue_certificate_title', 'local_certificate_management'),
            body: Templates.render('local_certificate_management/components/modal/select_certificate', {templates: response.templates}),
            isVerticallyCentered: true,
        });

        modal.setFooter(await Templates.render('local_certificate_management/components/modal/modal_footer_issue', {}));
        modal.getRoot().find('.btn.issue').on('click', async event => {
            const selectValue = modal.getRoot().find('.chose-template').val();
            const params = await getParams(root);

            await issueCertificateCall(selectValue, params, userId, userFullName, modal);
        });

        await modal.show();
    });
}

const regenCertificate = async (root, event) => {
    const response = await Repository.retrieveTemplates();
    const userId = $(event.currentTarget).attr('data-userid');
    const userFullName = $(event.currentTarget).attr('data-username');
    const modal = await ModalDefault.create({
        title: await getString('modal_regen_certificate', 'local_certificate_management'),
        body: Templates.render('local_certificate_management/components/modal/regen_certificate', {templates: response.templates}),
        isVerticallyCentered: true,
    });

    modal.setFooter(await Templates.render('local_certificate_management/components/modal/modal_footer_regen', {}));

    modal.getRoot().find('.btn.regen').on('click', async event => {
        const selectValue = modal.getRoot().find('.chose-template').val();

        const params = await getParams(root);


        await issueCertificateCall(selectValue, params, userId, userFullName, modal);
    });

    modal.getRoot().find('.btn.see').on('click', async event => {
        const params = await getParams(root);

        Repository.getCertificateUrl({
            courseId: Number(params.courseId),
            userId: Number(userId),
        }).then(async (response) => {
            window.open(response.certificate, "_blank");
        }).catch(async () => {
            modal.hide();
            await ModalDefault.create({
                title: await getString('modal_certified_issued_with_error_title', 'local_certificate_management'),
                body: await getString('modal_certified_issued_not_found_body', 'local_certificate_management'),
                show: true,
                isVerticallyCentered: true
            });
        });
    });

    await modal.show();
}

const issueCertificateCall = async (selectValue, params, userId, userFullName, modal) => {
    if(!await validateSelectValue(selectValue)) {
        return;
    }

    Repository.issueCertificate({
        templateId: Number(selectValue),
        courseId: Number(params.courseId),
        userId: Number(userId),
    }).then(async (response) => {
        console.log(response);
        modal.destroy();
        await ModalDefault.create({
            title: await getString('modal_certified_issued_with_success_title', 'local_certificate_management'),
            body: await getString('modal_certified_issued_with_success_body', 'local_certificate_management', {
                certificate: response.certificate,
                name: userFullName
            }),
            show: true,
            isVerticallyCentered: true
        });
    }).catch(async () => {
        modal.destroy();
        await ModalDefault.create({
            title: await getString('modal_certified_issued_with_error_title', 'local_certificate_management'),
            body: await getString('modal_certified_issued_with_error_body', 'local_certificate_management', userFullName),
            show: true,
            isVerticallyCentered: true
        });
    });
}

const validateSelectValue = async (selectValue) => {
    if(selectValue) {
        return true;
    }
    await ModalDefault.create({
        title: await getString('modal_certified_issued_with_error_title', 'local_certificate_management'),
        body: await getString('modal_certified_issued_with_error_body_select', 'local_certificate_management'),
        show: true,
        isVerticallyCentered: true
    });

    return false;
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