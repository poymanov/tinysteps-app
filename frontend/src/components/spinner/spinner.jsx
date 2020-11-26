import React from "react";
import PropTypes from "prop-types";

const Spinner = ({description}) => {
    return (<div className="text-center">
        <div className="spinner-border" style={{width: `150px`, height: `150px`}} role="status">
            <span className="sr-only">{description}</span>
        </div>
    </div>
    );
}

Spinner.propTypes = {
    description: PropTypes.string.isRequired
};

export default Spinner;
