import React from 'react'
import { Routes, Route } from "react-router-dom";

import Navup from '../components/common/navbar';
import Footer from '../components/common/footer';

import Homepage from '../PublicPages/homepage';
import AllJournalsPage from '../PublicPages/allJournalsPage';
import JournalDetailsPage from '../PublicPages/journalDetailsPage';
import Articles from '../components/journals/articles';



import Dashboard from '../AdminPages/dashboard/dashboard';
import AdminDash from '../AdminPages/dashboard/home';
import AddJournal from '../AdminPages/journal/addJournal';
import JournalPages from '../AdminPages/journal/journalPages';
import ContactDetails from '../AdminPages/contact/contactDetails';
import ViewJournal from '../AdminPages/journal/viewJournal';
import Edittors from '../AdminPages/journal/editors';
import Volume from '../AdminPages/volume/volume';
import AddVolume from '../AdminPages/volume/addvolume';
import AddIssue from '../AdminPages/volume/addissue';

import Article from '../AdminPages/article/article';

import AllManuscript from '../AdminPages/manuscript/allManuscript';
import AllReviewerRequest from '../AdminPages/requests/reviewerRequiest';
import AllEditorRequest from '../AdminPages/requests/editorRequest';
import AllAuthorRequest from '../AdminPages/requests/authorRequest';

import AddIndexing from '../AdminPages/indexing/addIndexing';
import ImpactFactorPage from '../AdminPages/journal/impactFactor';
import AddCertificatePage from '../AdminPages/journal/addCerificate';

import Login from '../AdminPages/form/login';

const AdminInterface = () => {
    return (
        <>

        


            <Routes>
                <Route path="/admin" element={<Dashboard />}>
                    <Route path="dashboard" element={<AdminDash />} />
                    <Route path="AddJournal" element={<AddJournal />} />
                    <Route path="AddJournal/:slug" element={<AddJournal />} />
                    <Route path="journal-pages/:slug" element={<JournalPages />} />
                    <Route path="contact-details" element={<ContactDetails />} />
                    <Route path="viewjournal" element={<ViewJournal />} />
                    <Route path="editors/:id" element={<Edittors />} />
                    <Route path="Volume" element={<Volume />} />
                    <Route path="volume/:id" element={<AddVolume />} />
                    <Route path="AddIssue/:id" element={<AddIssue />} />
                    <Route path="Article/:id" element={<Article />} />
                    <Route path="AllManuscript" element={<AllManuscript />} />
                    <Route path="AllReviewerRequest" element={<AllReviewerRequest />} />
                    <Route path="AllEditorRequest" element={<AllEditorRequest />} />
                    <Route path="AllAuthorRequest" element={<AllAuthorRequest />} />
                    <Route path="indexing/:id" element={<AddIndexing />} />
                    <Route path="ImpactFactor/:id" element={<ImpactFactorPage />} />
                    <Route path="certificate/:id" element={<AddCertificatePage />} />

                </Route>

                <Route path="Login" element={<Login />} />

                <Route path="*" element={<Dashboard />} />

            </Routes>
            {/* <Footer /> */}




        </>
    )
}

export default AdminInterface
