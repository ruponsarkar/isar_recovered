import React, { useState, useEffect } from "react";
import Card from "@mui/material/Card";
import AuthorText from '../common/AuthorText';

import PersonIcon from "@mui/icons-material/Person";
import BookmarksIcon from "@mui/icons-material/Bookmarks";
import SellIcon from "@mui/icons-material/Sell";
import Button from "@mui/material/Button";
import DocumentScannerIcon from "@mui/icons-material/DocumentScanner";
import PictureAsPdfIcon from "@mui/icons-material/PictureAsPdf";
import TextSnippetIcon from "@mui/icons-material/TextSnippet";
import Collapse from "react-bootstrap/Collapse";
import VisibilityIcon from "@mui/icons-material/Visibility";
import MenuBookIcon from "@mui/icons-material/MenuBook";
import ArrowCircleRightIcon from "@mui/icons-material/ArrowCircleRight";
import DownloadForOfflineIcon from "@mui/icons-material/DownloadForOffline";
import { Link } from "react-router-dom";
import fileDownload from "js-file-download";
import api from "../../API/api";

const LatestArticle = (props) => {
  const [objectsList, setObjectsList] = React.useState([]);

  const handleClick = (item) => {
    console.log(item.id);
    setObjectsList(
      objectsList.map((e) =>
        e.id === item.id ? { ...e, isOpen: !e.isOpen } : e
      )
    );
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

  useEffect(() => {
    if (props.latest) {
      setObjectsList(props.latest.map((e) => ({ ...e, isOpen: false })));
    }
  }, [props.latest]);

  return (
    <>
      <div className="pt-2">
        <Card>
          <div className="bg-primary p-2 text-white botton-line">
            Latest Articles
          </div>
          <div>
            {objectsList &&
              objectsList.map((el) => (
                <div className="card m-2 bg-light">
                  <div className=" m-2">
                    <div className="row">
                      <div className="col-md-6">
                        <button className="btn btn-success btn-sm">
                          Original Article
                        </button>
                      </div>
                      <div className="col-md-6 text-end">
                        <span className="text-secondary">
                          {
                            // new Date(el.created_at).getDate() + ', ' +
                            // new Date(el.created_at).toLocaleString("default", {
                            //     month: "long",
                            // }) +
                            // "-" +
                            // new Date(el.created_at).getFullYear()
                          }
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

                      {el.aname && (
                        <div className="col-12">
                          <PersonIcon fontSize="small" /> <AuthorText value={el.aname} />
                        </div>
                      )}

                      {el.designation && (
                        <div className="col-12">
                          <BookmarksIcon fontSize="small" /> <AuthorText value={el.designation} />
                        </div>
                      )}

                      {el.doi !== "null" && (
                        <>
                          {el.doi !== "undefined" && (
                            <>
                              {el.doi !== null && (
                                <div className="col-12">
                                  <SellIcon fontSize="small" /> DOI :{" "}
                                  <a href={el.doi} target="_blank">
                                    {el.doi}
                                  </a>
                                </div>
                              )}
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
                          onClick={() => handleClick(el)}
                          aria-controls="example-collapse-text"
                          aria-expanded={el.isOpen}
                        >
                          <DocumentScannerIcon fontSize="small" /> Abstract
                        </button>
                        &nbsp;
                        <a
                          href={`${process.env.REACT_APP_BACKEND}assets/articles/${el.file}`}
                          onClick={() => downloadArticle(el.id)}
                          download
                          target="_blank"
                          rel="noreferrer"
                          className="btn btn-secondary btn-sm"
                        >
                          <PictureAsPdfIcon fontSize="small" /> PDF
                        </a>
                        {/* <button
                          onClick={() => downloadArticle(el.id)}
                          className="btn btn-secondary btn-sm"
                        >
                          <PictureAsPdfIcon fontSize="small" /> PDF
                        </button> */}
                        &nbsp;
                        <a
                          href={`https://article.isarpublisher.com/viewArticle/${el.slug}`}
                          target="_blank"
                          className="btn btn-success btn-sm"
                        >
                          View Article <ArrowCircleRightIcon fontSize="small" />
                        </a>
                        {/* <span className="btn btn-secondary btn-sm float-end">
                          <VisibilityIcon />
                        </span> */}
                        &nbsp;
                        <Card>
                          <Collapse in={el.isOpen}>
                            {/* <div id="example-collapse-text" className="m-2">
                              {el.abstract}
                            </div> */}
                            <div
                              id="example-collapse-text"
                              className="m-2"
                              dangerouslySetInnerHTML={{ __html: el.abstract }}
                            />
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
    </>
  );
};

export default LatestArticle;
