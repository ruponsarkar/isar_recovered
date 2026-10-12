import React, {useState, useEffect} from 'react'
import { Routes, Route } from "react-router-dom";

import Navup from '../components/common/navbar';
import Footer from '../components/common/footer';

import Homepage from '../PublicPages/homepage';
import AllJournalsPage from '../PublicPages/allJournalsPage';
import JournalDetailsPage from '../PublicPages/journalDetailsPage';
import Articles from '../components/journals/articles';
import Manuscript from '../PublicPages/manuscript';
import JoinAuthorPage from '../PublicPages/joinAuthorpage';
import ProcessingFees from '../PublicPages/processingFees';
import ReviewProcessPage from '../PublicPages/reviewProcessPage';
import AboutUsPage from '../PublicPages/aboutUsPage';
import JoinReviewerPage from '../PublicPages/joinReviewerPage';
import JoinEditorPage from '../PublicPages/joinEditorPage';
import RefundPolicyPage from '../PublicPages/refundPolicyPage';

import PaymentPage from '../PublicPages/paymentPage';
import Payment from '../components/payment/payment';

import AuthorInstructionPage from '../PublicPages/authorInstructionsPage';
import OpenAccessPage from '../PublicPages/openAccessPage';
import ManuBtn from '../components/common/manuButton';
import CopyrightPolicyPage from '../PublicPages/copyRightPolicy';
import Checkout from '../components/payment/paymentTest';
import PublicationFees from '../PublicPages/publicationFees';
import ReviewGuidlinesPage from '../PublicPages/reviewersGuidlinesPage';
import EditorGuidlinesPage from '../PublicPages/editorsGuidlinesPage';
import EthicsPolicies from '../PublicPages/EthicsPolicies';
import RazorpayCheckout from '../razorpay';
import Representatives from '../PublicPages/Representatives';
import GenerativeAIPolicyPage from '../PublicPages/GenerativeAIPolicyPage';


import Login from '../AdminPages/form/login';

import api from '../API/api';
import { JournalContext } from '../Helper/Contex';

const PublicInterface = () => {

    const [journals, setJournals] =useState();
    const [topEditors, setTopEditors] =useState();
    const [indexings, setIndexings] =useState();
    const [load, setLoad] = useState(false);
    useEffect(() => {
        getJournals()
    }, []);
    const getJournals = () => {
        setLoad(true)
        api.getJournals()
            .then((res => {
                setLoad(false);
                console.log("==>", res);
                setJournals(res.data.journals);
                setTopEditors(res.data.topEditors);
                setIndexings(res.data.indexings_unique);
            }))
            .catch((err) => {
                console.log(err.response);
            })
    }

    useEffect(()=>{
        visitor();
    }, []);

    const visitor=()=>{
        api.visitor()
        .then((res)=>{
            console.log("Count==>", res);
        })
        .catch((err)=>{
            console.log(err.response);
        })
    }


    return (
        <JournalContext.Provider value={{journals, setJournals, topEditors, indexings}}>
            <ManuBtn/>

            <Navup />

            <Routes>
                <Route path="/old" element={<Homepage />} />
                <Route path="/" element={<Login />} />
                <Route path="/AllJournals" element={<AllJournalsPage />} />
                <Route path="/journal/:journal" element={<JournalDetailsPage />} />
                <Route path="/Articles/:Articles" element={<Articles />} />
                <Route path="/manuscript" element={<Manuscript />} />
                <Route path="/open-access-policy" element={<OpenAccessPage />} />
                <Route path="/CopyrightPolicyPage" element={<CopyrightPolicyPage />} />

                <Route path="/author-instruction" element={<AuthorInstructionPage />} />
                <Route path="/joinAuthor" element={<JoinAuthorPage />} />
                <Route path="/processingFees" element={<ProcessingFees />} />
                <Route path="/reviewProcess" element={<ReviewProcessPage />} />
                <Route path="/aboutUs" element={<AboutUsPage />} />
                <Route path="/JoinReviewer" element={<JoinReviewerPage />} />
                <Route path="/JoinEditor" element={<JoinEditorPage />} />
                <Route path="/RefundPolicy" element={<RefundPolicyPage />} />
                <Route path="/Payment" element={<PaymentPage />} />
                <Route path="/PaymentDetails" element={<Checkout />} />
                <Route path="/PublicationFee" element={<PublicationFees />} />
                <Route path="/ReviewGuidlines" element={<ReviewGuidlinesPage />} />
                <Route path="/EditorGuidlines" element={<EditorGuidlinesPage />} />
                <Route path="/EthicsPolicies" element={<EthicsPolicies />} />
                <Route path="/Representatives" element={<Representatives />} />
                <Route path="/GenerativeAIPolicy" element={<GenerativeAIPolicyPage />} />

                <Route path="/pay" element={<RazorpayCheckout />} />


                <Route path="/Login" element={<Login />} />

            </Routes>


            <Footer />
        </JournalContext.Provider>
    )
}

export default PublicInterface