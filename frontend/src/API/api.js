import API from "./apiConfig";

export default {

    getJournals() {
        return API.get('/getJournals')
    },
    journalDetails(journal) {
        return API.get(`/journalDetails/${journal}`)
    },
    journalDetailsForUpdate(journal) {
        return API.get(`/journalDetailsForUpdate/${journal}`)
    },

    getEditor(id) {
        return API.get(`/getEditor/${id}`)
    },

    getVolume(id) {
        return API.get(`/getVolume/${id}`);
    },
    addVolume(id, volume) {
        return API.post('/addVolume', { id, volume });
    },

    getIssue(id) {
        return API.get(`/getIssue/${id}`);
    },
    getIndexingById(id) {
        return API.get(`/getIndexingById/${id}`);
    },
    getCeertificateById(id) {
        return API.get(`/getCeertificateById/${id}`);
    },

    saveIssue(id, issue) {
        return API.post('/saveIssue', { id, issue });
    },
    updateIssue(data) {
        return API.post('/updateIssue', { data });
    },

    getArticle(id) {
        return API.get(`/getArticle/${id}`);
    },
    getManuscript() {
        return API.get(`/getManuscript`);
    },
    getReviewerRequest() {
        return API.get(`/getReviewerRequest`);
    },
    getEditorRequest() {
        return API.get(`/getEditorRequest`);
    },
    getAuthorRequest() {
        return API.get(`/getAuthorRequest`);
    },

    countSection() {
        return API.get('/countSection');
    },
    visitor() {
        return API.get('/visitor');
    },
    updateArticle() {
        return API.post('/updateArticle')
    },
    saveImpactFactor(data) {
        return API.post('/saveImpactFactor', { data })
    },
    getImpactFactor(j_id) {
        return API.get(`/getImpactFactor/${j_id}`)
    },
    deleteImpactFactor(id) {
        return API.post('/deleteImpactFactor', { id })
    },
    downloadArticle(id) {
        return API.get(`/downloadArticle/${id}`)
    },

    deleteArticle(id){
        return API.post('/deleteArticle', {id})
    },
    





    // login 
    register(formData) {
        return API.post('/register', { formData });
    },

    login(email, password) {
        return API.post('/login', { email, password })
    }




}