class GridUnit {
    static childColumns = [];
    static childRows = [];

    static render(sizesElements) {
        const parentElement = Object.values(sizesElements).find(element => element.parent);
        if (!parentElement) return '';

        // إعادة تهيئة المتغيرات
        this.childColumns = [];
        this.childRows = [];
        let style = '';

        for (const key in sizesElements) {
            const childElement = sizesElements[key];
            if (!childElement.parent) {
                this.processChildElement(childElement, parentElement, key);
            }
        }

        style += this.buildGridColumn();

        style += this.buildGridRow();
        style += "display:grid;";

        this.applyGridPositionsToChildren(sizesElements, this.childColumns, 'column');
        this.applyGridPositionsToChildren(sizesElements, this.childRows, 'row');

        return style;
    }

    static processChildElement(childElement, parentElement, childName) {


        if (childElement.width && childElement.height) {
            const columnStart = Math.ceil(parseFloat(childElement.x) / parseFloat(parentElement.width) * 16);
            const columnEnd = Math.ceil((parseFloat(childElement.x) + parseFloat(childElement.width)) / parseFloat(parentElement.width) * 16);
            const rowStart = Math.ceil(parseFloat(childElement.y) / parseFloat(parentElement.height) * 16);
            const rowEnd = Math.ceil((parseFloat(childElement.y) + parseFloat(childElement.height)) / parseFloat(parentElement.height) * 16);
            this.childColumns.push({
                id: childName,
                gridArea: `${rowStart} / ${columnStart} / ${rowEnd} / ${columnEnd}`,
                x : childElement.x
            });
        }
    }

    static buildGridColumn() {
        this.childColumns.sort((a, b) => a.x - b.x);
        this.adjustNegativeValues(this.childColumns, 'x');

        let style = '';

        this.childColumns.forEach((child, key) => {

            const value = (key === 0) ? child.x : child.x - this.childColumns[key - 1].x;

            style += value / 16 + "rem ";
        });

        return 'grid-template-columns: ' + style.trim() + ';';
    }

    static buildGridRow() {
        this.childRows.sort((a, b) => a.y - b.y);
        this.adjustNegativeValues(this.childRows, 'y');

        let style = '';

        this.childRows.forEach((child, key) => {
            const value = (key === 0) ? child.y : child.y - this.childRows[key - 1].y;
            style += 'minmax(' + value / 16 + "rem, max-content) ";
        });

        return 'grid-template-rows: ' + style.trim() + ';';
    }

    static applyGridPositionsToChildren(collection, children, type) {
        children.forEach(child => {
            const childElement = collection[child.id];
            collection[child.id].grid = collection[child.id].grid || {}; // التأكد من وجود خصائص الشبكة
            collection[child.id].grid['grid-area'] = child.gridArea; // إضافة grid-area
        });
    }

    static adjustNegativeValues(elements, key) {
        elements.forEach((value, index) => {
            if (value[key] < 0) {
                elements[index][key] = 0;
            }
        });
    }
}
