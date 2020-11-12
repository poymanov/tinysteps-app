import React, {Fragment} from "react";
import Header from "../header/header";
import Footer from "../footer/footer";

function Layout(props) {
    return (
        <Fragment>
            <Header />
            <main className="container mt-3 mb-5">
                {props.children}
            </main>
            <Footer />
        </Fragment>
    );
}

export default Layout;
