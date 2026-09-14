import React, { useState, useEffect } from "react";
import Card from "@mui/material/Card";

import PersonIcon from "@mui/icons-material/Person";
import BookmarksIcon from "@mui/icons-material/Bookmarks";
import SellIcon from "@mui/icons-material/Sell";
import Button from "@mui/material/Button";
import DocumentScannerIcon from "@mui/icons-material/DocumentScanner";
import PictureAsPdfIcon from "@mui/icons-material/PictureAsPdf";
import MenuBookIcon from "@mui/icons-material/MenuBook";
import TextSnippetIcon from "@mui/icons-material/TextSnippet";
import ArrowCircleRightIcon from "@mui/icons-material/ArrowCircleRight";
import DownloadForOfflineIcon from "@mui/icons-material/DownloadForOffline";
import { useParams } from "react-router-dom";
import api from "../../API/api";

import Collapse from "react-bootstrap/Collapse";

const Articles = () => {
  const params = useParams();

  const [open, setOpen] = useState(false);
  const [load, setLoad] = useState(false);
  const [article, setArticle] = useState([]);
  const [volume, setVolume] = useState();
  const [issue, setIssue] = useState();

  useEffect(() => {
    getArticle(params.Articles);
  }, []);

  const getArticle = (id) => {
    setLoad(true);
    api
      .getArticle(id)
      .then((res) => {
        setLoad(false);
        console.log(res);
        setArticle(res.data.article);
        setVolume(res.data.getVol);
        setIssue(res.data.getIssue);
      })
      .catch((err) => {
        console.log(err.response);
      });
  };

  const downloadArticle = (id) => {
    api
      .downloadArticle(id)
      .then((res) => {
        console.log("res==>", res);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const handleClick = (item) => {
    console.log(item.id);
    setArticle(
      article.map((e) =>
        e.id === item.id ? { ...e, isOpen: !e.isOpen } : e
      )
    );
  };

  return (
    <>
      <div className="container">
        <div className="pt-2">
          <Card>
            <div className="p-2">
              <h3>
                {volume && volume.name} -{" "}
                {issue && issue.name + `(${volume.year})`}
              </h3>
            </div>
          </Card>
          <Card>
            <div className="bg-primary p-2 text-white botton-line">
              All Articles
            </div>
            <div>
              {article &&
                article.map((el) => (
                  <div className="card m-2">
                    <div className=" m-2">
                      <div className="row">
                        <div className="col-md-6">
                          <button className="btn btn-success btn-sm">
                            Original Article
                          </button>
                        </div>
                        <div className="col-md-6 text-end">
                          {/* <span className='text-secondary'> */}
                          <span className="text-primary">
                            {el.count} <DownloadForOfflineIcon /> &nbsp; &nbsp;
                            {/* <a
                              href={`https://article.isarpublisher.com/viewArticle/${el.slug}`}
                            >
                              <strong>
                                View Article <ArrowCircleRightIcon />
                              </strong>
                            </a> */}
                          </span>
                        </div>
                      </div>

                      <div className="pt-3">
                        <div className="col-12">
                          <a
                            href={`https://article.isarpublisher.com/viewArticle/${el.slug}`}
                            className="text-dark"
                          >
                            <strong>{el.name}</strong>
                          </a>
                        </div>
                        <div className="col-12">
                          <PersonIcon fontSize="small" /> {el.aname}
                        </div>
                        <div className="col-12">
                          <BookmarksIcon fontSize="small" /> {el.designation}
                        </div>
                        {el.doi && (
                          <>
                            {el.doi !== "undefined" && (
                              <>
                                <div className="col-12">
                                  <SellIcon fontSize="small" /> DOI :{" "}
                                  <a href={el.doi} target="_blank">
                                    {el.doi}
                                  </a>
                                </div>
                              </>
                            )}
                          </>
                        )}
                        <div className="col-12">
                          <MenuBookIcon fontSize="small" /> Page : {el.page}
                        </div>

                        <div className="m-4">
                          <button
                            className="btn btn-secondary btn-sm"
                            // onClick={() => setOpen(!open)}
                            onClick={() => handleClick(el)}
                            aria-controls="example-collapse-text"
                            aria-expanded={open}
                          >
                            <DocumentScannerIcon fontSize="small" /> Abstract
                          </button>
                          &nbsp;
                          <a
                            onClick={() => downloadArticle(el.id)}
                            href={`${process.env.REACT_APP_BACKEND}assets/articles/${el.file}`}
                            target="_blank"
                            className="btn btn-secondary btn-sm"
                          >
                            <PictureAsPdfIcon fontSize="small" /> PDF
                          </a>
                          &nbsp;
                          <a
                            // href={`http://localhost:8000/viewArticle/${el.slug}`}
                            href={`https://article.isarpublisher.com/viewArticle/${el.slug}`}
                            target="_blank"
                            className="btn btn-success btn-sm"
                          >
                            View Article{" "}
                            <ArrowCircleRightIcon fontSize="small" />
                          </a>
                          <Card>
                            <Collapse in={el.isOpen}>
                              <div id="example-collapse-text" className="m-2">
                                {/* {el.abstract} */}
                                <div
                                  id="example-collapse-text"
                                  className="m-2"
                                  dangerouslySetInnerHTML={{
                                    __html: el.abstract,
                                  }}
                                />
                              </div>
                            </Collapse>
                          </Card>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
            </div>
          </Card>
        </div>
      </div>
    </>
  );
};

export default Articles;
