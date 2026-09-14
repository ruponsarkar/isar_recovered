import React, { useContext, useEffect, useState } from "react";
import BasicCard from "../home/card";
import TopEditors from "../home/topeditors";
import TractArticle from "../home/trackArticle";
import LoadingSkelton from "./skelton";
import LinearProgress from "@mui/material/LinearProgress";

import Card from "@mui/material/Card";
import CardActions from "@mui/material/CardActions";
import Button from "@mui/material/Button";
import Divider from "@mui/material/Divider";
import { useNavigate } from "react-router-dom";

import { JournalContext } from "../../Helper/Contex";
import IndexingJournal from "./indexing";
import { useParams } from "react-router-dom";
import api from "../../API/api";

const LeftSide = () => {
    const navigate = useNavigate();
    const params = useParams();
    const { journals, topEditors } = useContext(JournalContext);

    const [load, setLoad] = useState(false);
    const [indexing, setIndexing] = useState();
    const [factor, setFactor] = useState([]);
    const [certificate, setCertificate] = useState([]);

    useEffect(() => {
        getIndexingById();
        getImpactFactor();
        getCeertificateById();
    }, [params.journal]);

    const getIndexingById = () => {
        api.getIndexingById(params.journal).then((res) => {
            console.log("indexing22->", res);
            setIndexing(res.data.indexing);
        });
    };

    const getImpactFactor = () => {
        api
            .getImpactFactor(params.journal)
            .then((res) => {
                console.log("res==>Impact=>", res.data.impact);
                setFactor(res.data.impact);
            })
            .catch((err) => {
                console.log(err);
            });
    };

    const getCeertificateById = () => {
        setLoad(true);
        api
            .getCeertificateById(params.journal)
            .then((res) => {
                setLoad(false);
                console.log("getCeertificateById ", res);
                setCertificate(res.data.certificate);
            })
            .catch((err) => {
                console.log(err.response);
            });
    };

    return (
        <>
            {load ? (
                <>
                    <LinearProgress />

                    <LoadingSkelton />
                </>
            ) : (
                <>
                    <Card>
                        <div className="bg-primary p-2 text-white botton-line">
                            APPLICATIONS
                        </div>
                        <CardActions>
                            <Button size="small" onClick={() => navigate("/joinReviewer")}>
                                join as Reviewer
                            </Button>
                        </CardActions>
                        <CardActions>
                            <Button size="small" onClick={() => navigate("/joinEditor")}>
                                Join as Editor
                            </Button>
                        </CardActions>
                    </Card>
                    {factor.length ? (
                        <Card className="mt-2">
                            <div className="bg-primary p-2 text-white botton-line">
                                Impact Factor
                            </div>
                            {factor.map((el) => (
                                <CardActions>
                                    <a className="blink_me" href={el.link} target="_blank">
                                      <strong> {el.impact}</strong> 
                                    </a>
                                </CardActions>
                            ))}
                        </Card>
                    ) : (
                        ""
                    )}

                    <IndexingJournal indexings={indexing} />
                    {certificate.length ? (
                        <Card className="mt-2">
                            <div className="bg-primary p-2 text-white botton-line">
                                CERTIFICATE
                            </div>
                            <>
                                {certificate.map((cer) => (

                                    <div className="col-12 p-2">
                                        <img src={`${process.env.REACT_APP_BACKEND}assets/certificate/${cer.img}`} className='col-12' />
                                    </div>
                                ))}
                            </>
                        </Card>
                    ) : ''}
                </>
            )}
        </>
    );
};

export default LeftSide;
